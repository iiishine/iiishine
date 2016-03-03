<?php

namespace Bigecko\YD\Event\Filters;

use Illuminate\Routing\Redirector;
use Bigecko\YD\Event\AuthManger;

class MobileRequiredFilter
{

    /**
     * @var AuthManger
     */
    private $auth;

    /**
     * @var Redirector
     */
    private $redirector;

    function __construct(AuthManger $auth, Redirector $redirector)
    {
        $this->auth = $auth;
        $this->redirector = $redirector;
    }

    public function filter()
    {
        if (!$this->auth->check() || empty($this->auth->customer()->MPHONE)) {
            return $this->redirector->guest('auth/login');
        }

        $customer = $this->auth->customer();

        if ($customer->isMember() && !$customer->VALIDCARD) {
            return $this->redirector->guest('auth/login');
        }
    }
}
