<?php

use Bigecko\YD\Event\AuthManger;
use Bigecko\YD\HGCommon\Api\CheckMPhone;
use Bigecko\YD\HGCommon\Api\CLS;
use Bigecko\YD\HGCommon\Api\CLSErrorException;
use Bigecko\YD\HGCommon\Api\Gkx;
use Bigecko\YD\HGCommon\Exceptions\IDNoTailErrorException;
use Bigecko\YD\HGCommon\Exceptions\NoValidCardException;
use Bigecko\YD\HGCommon\Utils\MemberCardFinder;


class AuthController extends BaseController
{

    /**
     * @var AuthManger
     */
    private $auth;

    /**
     * @var CLS
     */
    private $cls;

    /**
     * @var CheckMPhone
     */
    private $checkMPhone;

    /**
     * @var Gkx
     */
    private $gkxWebapi;

    /**
     * @var MemberCardFinder
     */
    private $memberCardFinder;

    public function __construct(CLS $cls,
                                CheckMPhone $checkMPhone,
                                Gkx $gkxWebapi,
                                MemberCardFinder $memberCardFinder,
                                AuthManger $auth)
    {
        $this->auth = $auth;
        $this->cls = $cls;
        $this->checkMPhone = $checkMPhone;
        $this->gkxWebapi = $gkxWebapi;
        $this->memberCardFinder = $memberCardFinder;

        $this->beforeFilter('wechat_auth', array('only' => array(
            'getLogin',
            'postLogin',
        )));
    }


    /**
     * 显示留手机页面
     *
     * @return \Illuminate\View\View
     */
    public function getLogin()
    {
        if ($this->auth->check() && !empty($this->auth->customer()->MPHONE)
            && !($this->auth->customer()->isMember() && !$this->auth->customer()->VALIDCARD)) {
            return Redirect::intended('prize?' . Input::getQueryString());
        }

        return View::make('auth.login');
    }

    /**
     * 留手机页面提交请求
     */
    public function postLogin()
    {
        $nocardMsg = '对不起，该卡号已被停用请选择其他卡继续参加活动';
        if ($this->auth->check()
            && !empty($this->auth->customer()->MPHONE)
            && $this->auth->customer()->isMember()
            && !$this->auth->customer()->VALIDCARD) {
            return Redirect::back()->with('errorMsg', $nocardMsg)->withInput();
        }

        if ($this->auth->check() && !empty($this->auth->customer()->MPHONE)) {
            return Redirect::intended('prize?' . Input::getQueryString());
        }

        $validator = $this->loginValidator();

        if ($validator !== true) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        // 绑定微信
        if (!empty($this->auth->customer()->SER_OPENID)) {
            $userData = array(
                'ser_openid' => $this->auth->customer()->SER_OPENID,
                'mobile' => $this->auth->customer()->MPHONE
            );

            if ($this->auth->customer()->isMember()) {
                $userData['memberid'] = $this->auth->customer()->MEMBER_ID;
                $userData['type'] = 'bind';
            }
            else {
                $userData['type'] = 'reg_weixin';
            }

            $this->gkxWebapi->call('weixin/createUser', $userData);

            if (!is_null($this->auth->customer()->SUBSCRIBE_STATUS)) {
                $wechatUser = $this->gkxWebapi->call('weixin/getUserinfo', array(
                    'ser_openid' => $this->auth->customer()->SER_OPENID,
                    'show_wx_info' => '1',
                ));
                Event::fire('gkx.wx_getuser', array($wechatUser, true));
            }
        }

        if ($this->auth->customer()->isMember()) {
            $this->auth->customer()->VALIDCARD = true;
            $this->auth->customer()->save();
        }

        return Redirect::to('prize?' . Input::getQueryString());
    }

    /**
     * @return bool|\Illuminate\Validation\Validator
     */
    protected function loginValidator()
    {
        $mphone = Input::get('phone');

        // 验证短信验证码
        Validator::extend('sms_code_check', function($attribute, $value, $parameters) {
            $verifier = App::make('smsVerifier');
            return $verifier->checkCode(Input::get('phone'), $value);
        });

        // Validation rules
        $rules = array(
            'phone' => 'required|mobile',
        );
        if (!Config::get('app.debug')) {
            $rules += array(
                'sms_code' => 'required|sms_code_check',
                'captcha' => 'required|trimcaptcha',
            );
        }

        $validator = Validator::make(Input::all(), $rules, array(
            'captcha.trimcaptcha' => '图形验证码错误'
        ));

        if (!$validator->passes()) {
            return $validator;
        }

        try {
            $member = $this->memberCardFinder->findByPhone($mphone);
            $customerData = array(
                'MPHONE' => $mphone,
                'MEMBER_ID' => $member['MEMBER_ID'],
            );
        }
        catch (CLSErrorException $e) {
            $customerData = array('MPHONE' => $mphone);
        }

        if ($this->auth->check()) {
            $customer = $this->auth->customer();

            $c = $this->auth->findByPhone($mphone);

            if ($c && $c->ID != $customer->ID) {
                if (empty($c->SER_OPENID)) {
                    $c->delete();
                }
                else {
                    App::abort(200, '当前手机已经与其他微信帐号绑定过了');
                }
            }

            $customer->update($customerData);

        }
        else {
            $this->auth->logPhoneIn($mphone, $customerData);
        }

        return true;
    }

    public function getLogout()
    {
        $this->auth->logout();
        Session::flush();
        return Redirect::to('auth/login');
    }

}
