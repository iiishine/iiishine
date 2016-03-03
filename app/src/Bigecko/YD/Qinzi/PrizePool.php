<?php
namespace Bigecko\YD\Qinzi;

use Bigecko\YD\HGCommon\Api\EventBackend;
use Carbon\Carbon;
use Illuminate\Config\Repository as ConfigRepo;
use Log;

class PrizePool
{

    public function __construct(EventBackend $eventBackend, ConfigRepo $config)
    {
        $this->event = $eventBackend;
        $this->config = $config;
    }

    public function lottery($customer, $prizeId = null, $afterShare = 0)
    {
        if (is_null($prizeId) && !$this->checkLotteryable($customer, $afterShare)) {
            if ($customer->isMember()) {
                Log::info('已达到当前时间段获奖次数, 不发奖  ' . $customer->MPHONE . "   $afterShare");
            }
            return false;
        }

        $data = array(
            'area' => $area = $this->config->get('eventpage.act_area'),
            'device' => 'mobile',
            'user_type' => $customer->isMember() ? 'member' : 'guest',
            'mphone' => $customer->MPHONE,
            'member_id' => $customer->isMember() ? $customer->MEMBER_ID : '',
            'after_share' => $afterShare,
            'seckill' => 0,
            'source' => 'Wechat',
        );

        if (!is_null($prizeId)) {
            $data['prize_slug'] = $prizeId;
        }

        $command = is_null($prizeId) ? 'lottery' : 'pick';
        $result = $this->event->post("prize/$command", $data);

        if ($result->status == 200) {
            \PrizeRecord::create(array(
                'MPHONE' => $customer->MPHONE,
                'AFTER_SHARE' => $afterShare,
                'IS_MEMBER' => is_null($prizeId) ? $customer->isMember() : false,
            ));
            return $result;
        }
        else {
            return false;
        }
    }

    public function checkLotteryable($customer, $afterShare)
    {
        $ranges = $this->config->get('eventpage.prize_time_ranges');
        $now = Carbon::now();
        $tf = 'Y-m-d H:i:s';
        foreach($ranges as $setting) {
            $start = Carbon::createFromFormat($tf, $setting['start']);
            $end = Carbon::createFromFormat($tf, $setting['end']);
            $limit = $setting['limit'];

            if ($now >= $start && $now <= $end) {
                $times = \PrizeRecord::where('MPHONE', $customer->MPHONE)
                            ->where('AFTER_SHARE', $afterShare)
                            ->where('IS_MEMBER', true)
                            ->where('CREATED_AT', '>=', $start)
                            ->where('CREATED_AT', '<=', $end)
                            ->count();
                return $times < $limit;
            }
        }

        return false;
    }

    public function hasGuestPrize($mobile)
    {
        return \PrizeRecord::where('MPHONE', $mobile)
            ->where('IS_MEMBER', false)->exists();
    }
}
