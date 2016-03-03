<?php

use Bigecko\YD\Event\AuthManger;
use Bigecko\YD\HGCommon\Api\CLS;
use Bigecko\YD\HGCommon\Api\CLSErrorException;
use Bigecko\YD\HGCommon\Api\EventBackend;
use Bigecko\YD\HGCommon\Api\Gkx;
use Bigecko\YD\HGCommon\Exceptions\NoValidCardException;
use Bigecko\YD\HGCommon\Utils\MemberCardFinder;

class HomeController extends BaseController
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
     * @var Gkx
     */
    private $gkx;

    /**
     * @var MemberCardFinder
     */
    private $memberCardFinder;

    public function __construct(AuthManger $auth, CLS $cls, Gkx $gkx,
        EventBackend $eventBackend, MemberCardFinder $memberCardFinder)
    {
        $this->auth = $auth;
        $this->cls = $cls;
        $this->gkx = $gkx;
        $this->eventBackend = $eventBackend;
        $this->memberCardFinder = $memberCardFinder;
    }

    public function index()
    {
        if (Input::exists('ser_openid')) {
            Log::info('授权后跳转回活动: ' . Input::fullUrl());
        }

        if (Input::has('ser_openid')) {
            $serOpenid = Input::get('ser_openid');

            if ($this->auth->check()) {
                $customer = $this->auth->customer();

                $c = $this->auth->findBySerOpenid($serOpenid);
                if ($c && $c->ID != $customer->ID) {
                    // 删除重复出现的customer记录
                    if (empty($c->MPHONE)) {
                        $c->delete();
                    }
                    else {
                        App::abort(200, '当前微信帐号已经绑定过其他手机了');
                    }
                }
            }
            else {
                // 通过openid获取/创建并且登录用户
                $customer = $this->auth->logSerOpenidIn($serOpenid, array(
                    'SER_OPENID' => $serOpenid,
                ));
            }

            if (!is_null($customer->SUBSCRIBE_STATUS)) {
                try {
                    $wechatUser = $this->gkx->call('weixin/getUserinfo', array(
                        'ser_openid' => $serOpenid,
                        'show_wx_info' => '1',
                    ));
                }
                catch (Requests_Exception $e) {
                    Log::error("getUser 超时, ser_openid: $serOpenid ，尝试重新进入页面");
                    return Redirect::to(Input::fullUrl());
                }

                Event::fire('gkx.wx_getuser', array($wechatUser, true));
            }
        }

        return Redirect::to('prize?' . Input::getQueryString());
    }

    public function getMerchants()
    {
        return View::make('merchants', array(
            'customer' => $this->auth->customer()
        ));
    }

    /**
     * 我的礼包页面
     *
     * @return \Illuminate\View\View
     */
    public function getPoint()
    {
        $customer = $this->auth->customer();

        $prizes = array();
        $data = $this->eventBackend->get('user/prizes', array(
            'mphone' => $customer->MPHONE,
        ));
        $prizes = $data->prizes;

        return View::make('mypoint/point', array(
            'prizes' => $prizes,
            'customer' => $customer,
        ));
    }
}
