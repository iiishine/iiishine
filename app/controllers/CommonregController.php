<?php

use Bigecko\YD\Event\AuthManger;
use Bigecko\YD\HGCommon\Utils\MemberCardFinder;
use Bigecko\YD\HGCommon\Api\Gkx;
use Bigecko\YD\Qinzi\PrizePool;

class CommonregController extends BaseController
{
    /**
     * @var AuthManger
     */
    private $auth;
    /**
     * @var MemberCardFinder
     */
    private $memberCardFinder;

    /**
     * @var Gkx
     */
    private $gkx;

    /**
     * @var PrizePool
     */
    private $prizePool;

    /**
     * @param AuthManger $auth
     */
    public function __construct(AuthManger $auth,
                                MemberCardFinder $memberCardFinder,
                                PrizePool $prizePool,
                                Gkx $gkx) {
        $this->beforeFilter('commonreg_signmsg', array('only' => array(
            'getCallback',
        )));

        $this->beforeFilter('wechat_auth', array('only' => array(
            'getReg', 'getCallback',
        )));

        $this->beforeFilter('mobile_required', array('only' => array(
            'getReg',
        )));

        $this->auth = $auth;
        $this->memberCardFinder = $memberCardFinder;
        $this->prizePool = $prizePool;
        $this->gkx = $gkx;
    }

    public function getReg()
    {
        $mphone = $this->auth->customer()->MPHONE;
        $url = $this->commonregUrl(array(
            'origAccount' => $mphone,
            'name' => '',
            'address' => '',
            'phone' => $mphone,
        ), $this->auth->customer()->SER_OPENID);
        return Redirect::to($url);
    }

    /**
     * 生成通用注册页面url
     *
     * @param array $params
     *
     * @return string
     */
    protected function commonregUrl(array $params, $openid = null)
    {
        $params = array(
                'mgid' => Config::get("eventpage.commonreg.mgid")
            ) + $params;
        $signMsg = md5(http_build_query(array_merge($params, array(
            'secretKey' => Config::get('eventpage.commonreg.key'),
        ))));
        $params['signMsg'] = $signMsg;

        $url = Config::get('eventpage.commonreg.url') . '?' . http_build_query($params);

        if (!is_null($openid)) {
            $openidSk = md5($openid . Config::get('eventpage.ws_secretkey'));

            $url .= '&' . http_build_query(array(
                    'openid' => $openid,
                    'secretkey' => $openidSk,
            ));
        }

        return $url;
    }

    /**
     * 通用注册页面注册完成后回调
     *
     * @return \Illuminate\View\View|void
     * @throws \Bigecko\YD\HGCommon\Exceptions\IDNoTailErrorException
     * @throws \Bigecko\YD\HGCommon\Exceptions\NoValidCardException
     */
    public function getCallback()
    {
        $mphone = Input::get('origAccount');
        $customer = \Bigecko\YD\Qinzi\Customer::findByMobile($mphone);
        if (!$customer) {
            return 'mphone not found';
        }

        try {
            $member = $this->memberCardFinder->findByPhone($mphone);
        }
        catch (\Bigecko\YD\HGCommon\Api\CLSErrorException $e) {
            return '当前手机号是非卡友';
        }
        $customer->MEMBER_ID = $member['MEMBER_ID'];

        if (!is_null($customer->SUBSCRIBE_STATUS)) {
            try {
                $serOpenid = $customer->SER_OPENID;
                $wechatUser = $this->gkx->call('weixin/getUserinfo', array(
                    'ser_openid' => $serOpenid,
                    'show_wx_info' => '1',
                ));
                Event::fire('gkx.wx_getuser', array($wechatUser, true));
            }
            catch (Requests_Exception $e) {
                Log::error("getUser 超时, ser_openid: $serOpenid");
            }
        }

        $customer->VALIDCARD = true;
        if (empty($customer->NEW_MEMBER)) {
            $customer->NEW_MEMBER = \Carbon\Carbon::now();
        }

        $customer->save();

        if (!$this->prizePool->hasGuestPrize($customer->MPHONE)) {
            // 发放非卡友进入时的第一个奖品
            $p1 = $this->prizePool->lottery($customer, 'hongbaoquan');
            if (!$p1) {
                $this->prizePool->lottery($customer, 'hongbaodian');
            }
        }
        else {
            Log::info('已获取过非卡友奖品');
        }

        // 发放第二个奖品
        $result = $this->prizePool->lottery($customer, null, 1);
        if (!$result) {
            return Redirect::to('merchants');
        }

        return View::make("prize_share", array(
            'prize' => $result->prize,
            'customer' => $customer,
            'reg'   => true,
        ));
    }
}
