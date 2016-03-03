<?php namespace Bigecko\YD\Event;

use Bigecko\YD\HGCommon\Api\EventBackend;
use Illuminate\Cache\CacheManager;

class TrackingCodeComposer {

    /**
     * @var EventBackend
     */
    private $eventBackend;

    /**
     * @var CacheManager
     */
    private $cache;

    public function __construct(EventBackend $eventbackend, CacheManager $cache)
    {
        $this->eventBackend = $eventbackend;
        $this->cache = $cache;
    }

    public function compose($view)
    {
        $eventBackend = $this->eventBackend;
        $result = $this->cache->remember('yd_event_trackcode', 10, function() use ($eventBackend) {
            return $eventBackend->get('trackcode');
        });
        $view->with('trackcode', $result->google . $result->baidu);
    }
}
