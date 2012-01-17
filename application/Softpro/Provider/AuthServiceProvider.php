<?php
namespace Softpro\Provider;

use Silex\Application;

/**
 * auth service provider
 *
 * @category Softpro
 * @package  Auth
 * @author   Dmitry Groza <boxfrommars@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://github.com/boxfrommars/softpro.dev
 */
class AuthServiceProvider implements \Silex\ServiceProviderInterface
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
        $app['auth.dbtable'] = $app->share(
            function() use ($app) {
            }
        );

        $app['auth'] = $app->share(
            function() use ($app) {
                
            }
        );
    }
}
