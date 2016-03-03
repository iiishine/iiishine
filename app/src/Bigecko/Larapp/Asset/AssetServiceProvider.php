<?php namespace Bigecko\Larapp\Asset;

use Illuminate\Support\ServiceProvider;

class AssetServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bindShared('js', function($app) {
            return new JS();
        });
    }

}
