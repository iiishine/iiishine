<?php

namespace Bigecko\Larapp\Cms;

use Illuminate\Support\ServiceProvider;
use Bigecko\Larapp\Cms\Commands\InstallCommand;
use Bigecko\Larapp\Cms\Utils\Variable;

class CMSServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->package('bigecko/grcms', 'grcms', __DIR__);
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindShared('variable', function() {
            return new Variable($this->app->make('db'));
        });

        $this->app['grcms:install'] = $this->app->share(function($app) {
            return new InstallCommand($app);
        });

        $this->loadIncludes();

        $this->commands('grcms:install');
    }

    protected function loadIncludes()
    {
        $files = array('routes', 'filters');
        foreach ($files as $file) {
            include __DIR__ . '/' . $file . '.php';
        }
    }


}