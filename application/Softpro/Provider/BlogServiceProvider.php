<?php
namespace Softpro\Provider;

use Silex\Application;

class BlogServiceProvider implements \Silex\ServiceProviderInterface {
    
    public function register(Application $app)
    {
        $app['post.dbtable'] = $app->share(function() use ($app) {
            $postDbTable = new \Softpro\Blog\PostDbTable();
            $postDbTable->setDb($app['db']);
            return $postDbTable;
        });
        
        $app['post.service'] = $app->share(function() use ($app) {
            $postService = new \Softpro\Blog\PostService();
            $postService->setDbTable($app['post.dbtable']);
            return $postService;
        });
    }
}
