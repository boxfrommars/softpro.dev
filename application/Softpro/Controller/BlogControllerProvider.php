<?php

namespace Softpro\Controller;
use Silex\Application;

/**
 * blog controller provider
 * register Blog Provider and login/logout controllers
 *
 * @category Softpro
 * @package  Blog
 * @author   Dmitry Groza <boxfrommars@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://github.com/boxfrommars/softpro.dev
 */
class BlogControllerProvider implements \Silex\ControllerProviderInterface
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
        $app->register(new \Softpro\Provider\BlogServiceProvider());

        $controllers = new \Silex\ControllerCollection();

        $controllers->get(
            '/',
            function (Application $app) {
                $posts = $app['post.service']->getList();
                $app['layout.name'] = 'blog/index.twig';
                return $app['layout']->render(array('posts' => $posts));
            }
        );

        $controllers->get(
            '/{postId}',
            function(Application $app, $postId) {
                $post = $app['post.service']->get($postId);
                if (is_null($post)) return $app->abort(404);

                $comments = $app['comment.service']->getPostComments($post->getId());

                $app['layout.name'] = 'blog/view.twig';
                return $app['layout']->render(array('post' => $post, 'comments' => $comments));
            }
        )->assert('postId', '\d+');

        $controllers->post(
            '/{postId}/comment/add',
            function(Application $app, $postId) {
                $comment = new \Softpro\Blog\Comment($app['request']->get('comment'));
                $comment->setPostId($postId);
                $success = $app['comment.service']->save($comment);

                if ($success) {
                    $app['session']->setFlash('success', 'comment successfully added!');
                } else {
                    $app['session']->setFlash('error', '<b>error!</b> comment not added!');
                }
                return $app->redirect('/blog/' . $postId);
            }
        )->assert('postId', '\d+');

        return $controllers;
    }

}