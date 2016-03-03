<?php namespace Bigecko\Larapp\Routing;

class RoutingServiceProvider extends \Illuminate\Routing\RoutingServiceProvider {

    protected function registerUrlGenerator()
    {
        $this->app['url'] = $this->app->share(function($app) {
            $routes = $app['router']->getRoutes();

            $urlGenerator = new UrlGenerator($routes, $app->rebinding('request', function($app, $request) {
                $app['url']->setRequest($request);
            }));
            $urlGenerator->setCacheManager($app->make('cache'));

            // Set asset url prefix.
            if (isset($app['config']['app.asset_url_prefix'])) {
                $urlGenerator->setAssetUrlPrefix($app['config']['app.asset_url_prefix']);
            }
            else if (defined('ROOT_INDEX')){
                $urlGenerator->setAssetUrlPrefix('public');
            }

            return $urlGenerator;
        });
    }

}


