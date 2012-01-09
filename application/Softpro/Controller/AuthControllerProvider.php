<?php

namespace Softpro\Controller;
use Silex\Application;

class AuthControllerProvider implements \Silex\ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $app->register(new \Softpro\Provider\AuthServiceProvider());
        
        $controllers = new \Silex\ControllerCollection();

        $controllers->get('/login', function (Application $app) {
            
        });
        $controllers->get('/logout', function (Application $app) {
            
        });
        return $controllers;
    }
    
}