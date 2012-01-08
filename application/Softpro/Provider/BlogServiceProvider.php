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
        
        $app['user.dbtable'] = $app->share(function() use ($app) {
            $userDbTable = new \Softpro\User\UserDbTable();
            $userDbTable->setDb($app['db']);
            return $userDbTable;
        });
        
        $app['user.service'] = $app->share(function() use ($app) {
            $userService = new \Softpro\User\UserService();
            $userService->setDbTable($app['user.dbtable']);
            return $userService;
        });
        
        $app['comment.dbtable'] = $app->share(function() use ($app) {
            $commentDbTable = new \Softpro\Blog\CommentDbTable();
            $commentDbTable->setDb($app['db']);
            return $commentDbTable;
        });
        
        $app['comment.service'] = $app->share(function() use ($app) {
            $commentService = new \Softpro\Blog\CommentService();
            $commentService->setDbTable($app['comment.dbtable']);
            return $commentService;
        });
        
        $app['post.service'] = $app->share(function() use ($app) {
            $postService = new \Softpro\Blog\PostService();
            $postService->setDbTable($app['post.dbtable']);
            return $postService;
        });
    }
}
