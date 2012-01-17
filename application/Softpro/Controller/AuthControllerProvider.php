<?php

namespace Softpro\Controller;
use Silex\Application;

/**
 * auth controller provider
 * register ServiceProvider and login/logout controllers
 *
 * @category Softpro
 * @package  Auth
 * @author   Dmitry Groza <boxfrommars@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://github.com/boxfrommars/softpro.dev
 */
class AuthControllerProvider implements \Silex\ControllerProviderInterface
{

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return \Silex\ControllerCollection
     */
    public function connect(Application $app)
    {
        $app->register(new \Softpro\Provider\AuthServiceProvider());

        $controllers = new \Silex\ControllerCollection();

        $controllers->get(
            '/login',
            function (Application $app) {
            }
        );

        $controllers->get(
            '/logout',
            function (Application $app) {
            }
        );

        return $controllers;
    }

}