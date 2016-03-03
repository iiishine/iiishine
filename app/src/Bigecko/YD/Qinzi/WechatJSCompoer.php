<?php

namespace Bigecko\YD\Qinzi;

use Bigecko\YD\Event\AuthManger;
use Bigecko\YD\HGCommon\Api\EventBackend;
use Overtrue\Wechat\Js as WechatJs;
use Bigecko\Larapp\Support\Facades\JS;

class WechatJSCompoer
{

    /**
     * @var EventBackend
     */
    private $eventBackend;

    /**
     * @var WechatJs
     */
    private $wechatJs;
    /**
     * @var AuthManger
     */
    private $authManger;


    function __construct(EventBackend $eventBackend, WechatJs $wechatJs, AuthManger $authManger)
    {
        $this->eventBackend = $eventBackend;
        $this->wechatJs = $wechatJs;
        $this->authManger = $authManger;
    }

    public function compose($view)
    {
        $res = $this->eventBackend->get('share/res');
        $isMember = $this->authManger->check() && $this->authManger->customer()->isMember();

        JS::settings(array('isMember' => $isMember));

        $view->with('wechatJs', $this->wechatJs);
        $view->with('shareres', $res);
    }
}
