<?php
namespace Softpro\Provider;

use Silex\Application;

/**
 * auth service provider
 *
 * @category Softpro
 * @package  User
 * @author   Dmitry Groza <boxfrommars@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://github.com/boxfrommars/softpro.dev
 */
class UserServiceProvider implements \Silex\ServiceProviderInterface
{

    /**
     * Registers services on the given app.
     *
     * @param Application $app An Application instance
     *
     * @return null
     */
    public function register(Application $app)
    {
        $app['user.dbtable'] = $app->share(
            function() use ($app) {
                $userDbTable = new \Softpro\User\UserDbTable;
                $userDbTable->setDb($app['db']);
                return $userDbTable;
            }
        );

        $app['user.service'] = $app->share(
            function() use ($app) {
                $userService = new \Softpro\User\UserService();
                $userService->setDbTable($app['user.dbtable']);
                return $userService;
            }
        );
    }
}
