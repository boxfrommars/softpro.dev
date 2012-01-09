<?php

use Silex\WebTestCase;
use Softpro\Blog\Post;
use Softpro\Blog\Comment;

class CommentPostServiceTest extends WebTestCase
{
    public function createApplication()
    {
        require PATH_TO_APP . '/app.php';
        return $app;
    }
    
    /**
     * @dataProvider commentGetProvider
     * @covers \Softpro\Blog\CommentService::get
     */
    public function testGet($id)
    {
        $expectedData = $this->app['comment.dbtable']->get($id);
        $comment = $this->app['comment.service']->get($id);
        
        if (empty($expectedData)) {
            $this->assertEquals(null, $comment);
        } else {
            $this->assertTrue($comment instanceof \Softpro\Blog\Comment);
        }
    }
    
    public function commentGetProvider()
    {
        return array(
            array(1),
            array(2),
            array(50),
            array(1000),
        );
    }
    
    /**
     * @covers \Softpro\Blog\CommentService::getList
     */
    public function testGetList()
    {
        $expectedRows = $this->app['comment.dbtable']->getList();
        $items = $this->app['comment.service']->getList();
        
        $this->assertEquals(count($expectedRows), count($items));
    }
    
    /**
     * @dataProvider postCommentsProvider
     * @covers \Softpro\Blog\CommentService::getPostComments
     */
    public function testGetPostComments($postId)
    {
        $comments = $this->app['comment.service']->getPostComments($postId);
        
        foreach ($comments as $comment) {
            $this->assertTrue($comment instanceof \Softpro\Blog\Comment);
            $this->assertEquals($comment->getPostId(), $postId);
        }
    }
    
    public function postCommentsProvider()
    {
        return array(
            array(1),
//            array(2),
//            array(10),
//            array(1000),
        );
    }
    
    /**
     * @dataProvider isValidProvider
     * @covers \Softpro\Blog\CommentService::isValid
     */
    public function testIsValid($itemData, $expected)
    {
        $item = new Comment($itemData);
        $this->assertEquals($expected, $this->app['comment.service']->isValid($item));
    }
    
    public function isValidProvider()
    {
        return array(
            array(array(
                'id' => 1,
                'postId' => 1,
                'content' => 'whata fuck!',
            ), false),
            array(array(
                'id' => 100,
                'postId' => 1,
                'content' => 'whata fuck!',
            ), false),
            array(array(
                'id' => 100,
                'postId' => 1,
                'authorId' => 1,
                'authorEmail' => 'boxfrommars@gmail.com',
                'authorName' => 'Dmitry Groza',
                'content' => 'whata fuck!',
            ), true),
            array(array(
                'id' => 101,
                'postId' => 1,
                'authorId' => 1,
                'authorEmail' => 'boxfrommars@gmail.com',
                'authorName' => 'Dmitry Groza',
                'content' => 'whata fuck!',
            ), true),
            array(array(
                'id' => 1,
                'postId' => 1,
                'authorId' => 1,
                'authorEmail' => 'boxfrommars@gmail.com',
                'authorName' => 'Dmitry Groza',
                'content' => 'whata fuck!',
            ), true),
            array(array(
                'id' => 1,
                'postId' => 50,
                'authorId' => 1,
                'authorEmail' => 'boxfrommars@gmail.com',
                'authorName' => 'Dmitry Groza',
                'content' => 'whata fuck!',
            ), true),
            array(array(
                'id' => 1,
                'postId' => 1,
                'authorId' => 50,
                'authorEmail' => 'boxfrommars@gmail.com',
                'authorName' => 'Dmitry Groza',
                'content' => 'whata fuck!',
            ), true),
            array(array(
                'authorId' => 1,
                'content' => 'whata fuck!',
            ), false),
            array(array(
                'postId' => 1,
                'content' => 'whata fuck!',
            ), false),
            array(array(
                'id' => 16,
            ), false),
        );
    }
    
    /**
     * @dataProvider saveProvider
     * @covers \Softpro\Blog\CommentService::save
     */
    public function testSave($itemData)
    {
        $item = new Comment($itemData);
        if ($this->app['comment.service']->isValid($item)) {
            $count = $this->app['comment.service']->save($item);
            $this->assertEquals(1, $count);
        } else {
            try {
                $count = $this->app['comment.service']->save($item);
            } catch (\Softpro\InvalidItemException $expected) {
                return;
            }
            $this->fail('An expected exception has not been raised.');
        }
    }
    
    public function saveProvider()
    {
        return array(
            array(array(
                'id' => 1,
                'postId' => 1,
                'content' => 'whata fuck!',
            )),
            array(array(
                'id' => 100,
                'postId' => 1,
                'content' => 'whata fuck!',
            )),
            array(array(
                'id' => 100,
                'postId' => 1,
                'authorId' => 1,
                'authorEmail' => 'boxfrommars@gmail.com',
                'authorName' => 'Dmitry Groza',
                'content' => 'whata fuck!',
            )),
            array(array(
                'id' => 101,
                'postId' => 1,
                'authorId' => 1,
                'authorEmail' => 'boxfrommars@gmail.com',
                'authorName' => 'Dmitry Groza',
                'content' => 'whata fuck!',
            )),
            array(array(
                'id' => 102,
                'postId' => 50,
                'authorId' => 1,
                'authorEmail' => 'boxfrommars@gmail.com',
                'authorName' => 'Dmitry Groza',
                'content' => 'whata fuck!',
            )),
            array(array(
                'id' => 103,
                'postId' => 1,
                'authorId' => 50,
                'authorEmail' => 'boxfrommars@gmail.com',
                'authorName' => 'Dmitry Groza',
                'content' => 'whata fuck!',
            )),
            array(array(
                'id' => 1,
                'postId' => 1,
                'authorId' => 1,
                'authorEmail' => 'boxfrommars@gmail.com',
                'authorName' => 'Dmitry Groza',
                'content' => 'whata fuck!',
            )),
            array(array(
                'authorId' => 1,
                'content' => 'whata fuck!',
            )),
            array(array(
                'postId' => 1,
                'content' => 'whata fuck!',
            )),
            array(array(
                'id' => 16,
            )),
        );
    }
    
    /**
     * @dataProvider deleteProvider
     * @covers \Softpro\Blog\CommentService::delete
     */
    public function testDelete($id)
    {
        $isExist = $this->app['comment.service']->isExist($id);
        $count = $this->app['comment.service']->delete($id);
        
        $this->assertEquals($isExist ? 1 : 0, $count);
    }
    
    public function deleteProvider()
    {
        return array(
            array(1),
            array(2),
            array(100),
        );
    }
    
}
