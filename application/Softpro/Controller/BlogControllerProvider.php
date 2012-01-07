<?php

namespace Softpro\Controller;
use Silex\Application;

class BlogControllerProvider implements \Silex\ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $app->register(new \Softpro\Provider\BlogServiceProvider());
        
        $controllers = new \Silex\ControllerCollection();

        $controllers->get('/', function (Application $app) {
            $posts = $app['post.service']->getList();
            $content = $app['twig']->render('blog/index.twig', array('posts' => $posts));
            return $app['layout']->render(array('content' => $content));
        });
        
        $controllers->get('/{postId}', function(Application $app, $postId) {
            $post = $app['post.service']->get($postId);
            if (is_null($post)) return $app->abort(404);
            return $app['layout']->render(array('content' => 'this is post #' . $post->getId()));
        })->assert('post', '\d+');

        return $controllers;
    }
    
}