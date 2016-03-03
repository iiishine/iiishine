<?php namespace Bigecko\Larapp\Support\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * @see \Bigecko\Larapp\Asset\JS
 */
class JS extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'js'; }

}
