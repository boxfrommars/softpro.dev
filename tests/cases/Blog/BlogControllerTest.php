<?php
use Silex\WebTestCase;
use Softpro\Blog\Post;

class BlogControllerTest extends WebTestCase
{
    public function createApplication()
    {
        require __DIR__ . '/../../../application/app.php';
        return $app;
    }

    public function testIndexHTML()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/blog/');
        
        $this->assertTrue($client->getResponse()->isOk());
        
        
        $this->assertEquals(1, count($crawler->filter('html:contains("Blog")')));
        
        $posts = $this->app['post.service']->getList();
        foreach ($posts as $post) {
            
            /* @var $post \Softpro\Blog\Post */
            $this->assertEquals(1, count($crawler->filter('html:contains("#' . $post->getId() . '")')));
        }
        
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
}
