<?php namespace Bigecko\Larapp\Foundation;

use Bigecko\Larapp\Routing\RoutingServiceProvider;

class Application extends \Illuminate\Foundation\Application {

    protected function registerRoutingProvider()
    {
        $this->register(new RoutingServiceProvider($this));
    }

}
