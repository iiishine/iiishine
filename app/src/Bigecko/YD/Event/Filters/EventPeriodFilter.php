<?php

namespace Bigecko\YD\Event\Filters;

use Bigecko\YD\Event\AuthManger;
use Bigecko\YD\HGCommon\Api\EventBackend;
use Illuminate\Config\Repository as ConfigRepo;
use Illuminate\Cache\CacheManager;
use Illuminate\Routing\Redirector;
use Carbon\Carbon;
use Config;

class EventPeriodFilter
{
    /**
     * @var EventBackend
     */
    protected $eventBackend;

    /**
     * @var ConfigRepo
     */
    protected $config;

    /**
     * @var CacheManager
     */
    protected $cache;

    /**
     * @var Redirector
     */
    protected $redirector;

    /**
     * @var $authMenger
     */
    protected $authMenger;

    public function __construct(AuthManger $authMenger,EventBackend $eventBackend, ConfigRepo $config, CacheManager $cache, Redirector $redirector)
    {
        $this->authMenger = $authMenger;
        $this->eventBackend = $eventBackend;
        $this->config = $config;
        $this->cache = $cache;
        $this->redirector = $redirector;
    }

    public function filter()
    {
        $eventBackend = $this->eventBackend;
        $eventInfo = $eventBackend->get('event/info'); // 查询活动信息
        $now = Carbon::now();
        $format = 'Y-m-d H:i:s';
        $start = Carbon::createFromFormat($format, $eventInfo->start_at);
        $end = Carbon::createFromFormat($format, $eventInfo->end_at);

        $customer = $this->authMenger->customer();

        if ($now < $start || $now > $end) {
            // 活动过期
            $mphone = '';
            if (!empty($customer) || !empty($customer->MPHONE)) {
                $mphone = '?phone=' . $customer->MPHONE;
            }
            return \Redirect::to(Config::get('eventpage.eventadmin.base_url') . '/overdue-page' . $mphone);
        } else {
            if (empty($customer) || empty($customer->MPHONE)) {
                return;
            }
            // 查询用户是否参加过
            $isEvent = $eventBackend->get('event/isuser', array('mphone' => $customer->MPHONE));
            if (empty($isEvent) || !$isEvent->code) {
                // 记录用户参加此活动
                $eventBackend->post('event/adduserlog', array('mphone' => $customer->MPHONE));
            }
            return;
        }
    }
}
