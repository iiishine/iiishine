<?php
use Bigecko\YD\Event\AuthManger;
use Bigecko\YD\Qinzi\PrizePool;
use Bigecko\YD\HGCommon\Api\EventBackend;

class PrizeController extends BaseController
{

    /**
     * @var AuthManger
     */
    private $auth;

    /**
     * @var PrizePool
     */
    private $prizePool;

    /**
     * @var EventBackend
     */
    private $eventBackend;

    /**
     * @param AuthManger   $authManager
     * @param PrizePool    $prizePool
     * @param EventBackend $eventBackend
     */
    public function __construct(AuthManger $authManager, PrizePool $prizePool, EventBackend $eventBackend)
    {
        $this->beforeFilter('wechat_auth');
        $this->beforeFilter('mobile_required');

        $this->auth = $authManager;
        $this->prizePool = $prizePool;
        $this->eventBackend = $eventBackend;
    }

    public function getIndex()
    {
        $customer = $this->auth->customer();

        // 卡友调用后台接口发奖
        if ($customer->isMember()) {
            $result = $this->prizePool->lottery($customer);

            if ($result) {
                $customer->MEMBER_PRIZE = true;
            }
        }
        // 非卡友, 记录进入活动状态
        else {
            $result = $customer->GUEST_PRIZE
                ? false
                : (object)array('prize' => $this->createGuestPrize($customer, 'join'));
            $customer->GUEST_PRIZE = true;
        }

        $customer->save();

        if (!$result) {
            if (!$customer->isMember()) {
                return View::make('prize_guest_again');
            }

            // 处理卡友
            if ($this->prizePool->checkLotteryable($customer, true)) {
                // DUANG再领一个红包模板
                return View::make('prize_duang');
            } else {
                return Redirect::to('merchants?tip=1');
            }
        }
        else {
            $prize = $result->prize;

            return View::make("prize_join", array(
                'prize' => $prize,
                'customer' => $customer,
            ));
        }

    }

    /**
     * @return \Illuminate\View\View
     */
    public function getShare()
    {
        $customer = $this->auth->customer();

        // 非卡友提示有一个红包未领取
        if (!$customer->isMember()) {
            $customer->GUEST_SHARE = true;
            $customer->save();
            return View::make('prize_guest_again');
        }

        $result = $this->prizePool->lottery($customer, null, 1);

        $customer->MEMBER_SHARE = true;
        $customer->save();

        if (!$result) {
            return Redirect::to('merchants');
        }

        $prize= $result->prize;

        return View::make('prize_share', array(
            'prize' => $prize,
            'customer' => $customer,
        ));
    }
}
