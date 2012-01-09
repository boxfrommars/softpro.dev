<?php
namespace Softpro\Provider;

use Silex\Application;

class AuthServiceProvider implements \Silex\ServiceProviderInterface {
    
    public function register(Application $app)
    {
        $app['auth.dbtable'] = $app->share(function() use ($app) {});
    }
}
