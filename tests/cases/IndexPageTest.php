<?php

require_once __DIR__ . '/../../../Silex/autoload.php';

use Silex\WebTestCase;

class IndexPageTest extends WebTestCase
{
    public function createApplication()
    {
        require __DIR__ . '/../../application/app.php';
        return $app;
    }

    public function testHTML()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');
        
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertEquals(1, count($crawler->filter('html:contains("SoftPro")')));
        $this->assertEquals(1, count($crawler->filter('html:contains("hello")')));
        $this->assertEquals(1, count($crawler->filter('html:contains("world")')));
    }
}
