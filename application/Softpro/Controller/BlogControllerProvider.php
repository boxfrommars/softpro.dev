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
            
            $app['layout.name'] = 'blog/index.twig';
            return $app['layout']->render(array('posts' => $posts));
        });
        
        $controllers->get('/{postId}', function(Application $app, $postId) {
            $post = $app['post.service']->get($postId);
            if (is_null($post)) return $app->abort(404);
            
            $comments = $app['comment.service']->getPostComments($post->getId());
            
            $app['layout.name'] = 'blog/view.twig';
            return $app['layout']->render(array('post' => $post, 'comments' => $comments));
        })->assert('post', '\d+');

        return $controllers;
    }
    
}