<?php

namespace Bigecko\YD\Event\Filters;

use Illuminate\Routing\Redirector;
use Bigecko\YD\Event\AuthManger;

class MemberRequiredFilter
{

    /**
     * @var Redirector
     */
    private $redirector;

    /**
     * @var AuthManger
     */
    private $auth;

    function __construct(AuthManger $auth, Redirector $redirector)
    {
        $this->redirector = $redirector;
        $this->auth = $auth;
    }

    public function filter()
    {
        if (!$this->auth->check() || !$this->auth->customer()->isMember()) {
            return $this->redirector->to('commonreg/notmbr');
        }
    }
}
