<?php

namespace Bigecko\Larapp\Pagination;

class PaginationServiceProvider extends \Illuminate\Pagination\PaginationServiceProvider
{
    public function register()
    {
        $this->app->bindShared('paginator', function($app)
        {
            $paginator = new Environment($app['request'], $app['view'], $app['translator']);

            $paginator->setViewName($app['config']['view.pagination']);

            $app->refresh('request', $paginator, 'setRequest');

            return $paginator;
        });
    }
}