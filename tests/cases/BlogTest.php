<?php

require_once __DIR__ . '/../../../Silex/autoload.php';

use Silex\WebTestCase;
use Softpro\Blog\Post;

class BlogTest extends WebTestCase
{
    public function createApplication()
    {
        require __DIR__ . '/../../application/app.php';
        return $app;
    }

    public function testIndexHTML()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/blog/');
        
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertEquals(1, count($crawler->filter('html:contains("blog")')));
        $this->assertEquals(1, count($crawler->filter('html:contains("post #1")')));
    }

    public function testPostHTML()
    {
        $client = $this->createClient();
        
        $postId = 1;
        
        $crawler = $client->request('GET', '/blog/' . $postId);
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertEquals(1, count($crawler->filter('html:contains("post #' . $postId . '")')));
        
        $postId = 100;
        $crawler = $client->request('GET', '/blog/' . $postId);
        $this->assertTrue($client->getResponse()->isNotFound());
        
    }
    
    public function testPostServiceGet()
    {
        $postId = 1; // есть запись
        $post = $this->app['post.service']->get($postId);
        
        $this->assertTrue($post instanceof \Softpro\Blog\Post);
        
        $this->assertEquals($postId, $post->getId());
        $this->assertTrue($post->hasTitle());
        $this->assertTrue($post->hasId_user());
        $this->assertTrue($post->hasContent());
        $this->assertTrue($post->hasUpdated_at());
        $this->assertTrue($post->hasCreated_at());
        $this->assertFalse($post->hasSomething());
        
        $postId = 100; // записи нет
        $post = $this->app['post.service']->get($postId);
        $this->assertEquals(null, $post);
    }
    
    public function testPostServiceGetList()
    {
        $list = $this->app['post.service']->getList();
        foreach ($list as $post) {
            $this->assertTrue($post instanceof \Softpro\Blog\Post);
        }
    }
    
    public function testPostDbTableGetList()
    {
        $list = $this->app['post.dbtable']->getList();
        foreach ($list as $post) {
            $this->assertTrue(array_key_exists('id', $post));
            $this->assertTrue(array_key_exists('title', $post));
            $this->assertTrue(array_key_exists('content', $post));
        }
    }
    
    /**
     * @expectedException \Softpro\InvalidPropertyException
     */
    public function testPostInvalidProperty()
    {
        $post = new \Softpro\Blog\Post(array(
            'id' => 4,
        ));
        $post->getSomething();
    }
    
    /**
     * @expectedException \Softpro\InvalidPropertyException
     */
    public function testPostInvalidMethod()
    {
        $post = new \Softpro\Blog\Post(array(
            'id' => 4,
        ));
        $post->doSomething();
    }
    
    /**
     * @dataProvider postServiceSaveSuccessProvider
     */
    public function testPostServiceSave($postRaw)
    {
        $post = new Post($postRaw);
        $count = $this->app['post.service']->save($post);
        $this->assertEquals(1, $count);
        
        $insertedPostId = $this->app['db']->lastInsertId();
        $insertedPost = $this->app['post.service']->get($insertedPostId);
        
        $this->assertTrue($insertedPost instanceof Post);
        $this->assertEquals($insertedPost->getTitle(), $post->getTitle());
        $this->assertEquals($insertedPost->getContent(), $post->getContent());
        $this->assertEquals($insertedPost->getId_user(), $post->getId_user());
        
        return $insertedPostId;
    }
    
    /**
     * @dataProvider postServiceSaveFailureProvider
     */
    public function testPostServiceFailureSave($postRaw)
    {
        $post = new Post($postRaw);
        $count = $this->app['post.service']->save($post);
        $this->assertEquals(0, $count);
    }
    
    /**
     * @dataProvider postServiceSaveWithExceptionProvider
     * @expectedException PDOException
     */
    public function testPostServiceSaveWithException($postRaw)
    {
        $post = new Post($postRaw);
        $count = $this->app['post.service']->save($post);
    }
    
    public function testPostServiceDelete()
    {
        $posts = $this->app['post.service']->getList();
        $post = array_pop($posts);
        
        $count = $this->app['post.service']->delete($post->getId());
        
        $this->assertEquals(1, $count);
        $this->assertEquals(null, $this->app['post.service']->get($post->getId()));
    }
    
    public function testPostServiceDeleteFailure()
    {
        $postId = 1000000;
        while (!is_null($this->app['post.service']->get($postId))) {
            $postId += 100; 
        }
        $count = $this->app['post.service']->delete($postId);
        $this->assertEquals(0, $count);
    }
    
    public function postServiceSaveWithExceptionProvider()
    {
        return array(
            array(array('title' => 'another article')),
            array(array()),
        );
    }
    
    public function postServiceSaveFailureProvider()
    {
        return array(
            array(array('id' => 6)),
        );
    }
    
    public function postServiceSaveSuccessProvider()
    {
        return array(
            array(array('id_user' => 1, 'title' => 'another article')),
        );
    }
}
