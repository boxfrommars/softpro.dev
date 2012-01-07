<?php
require_once __DIR__ . '/../../../Silex/autoload.php';
require __DIR__ . '/../../application/Softpro/DbWebTestCase.php';
require __DIR__ . '/../../application/Softpro/ArrayDataSet.php';

use Softpro\DbWebTestCase;
use Softpro\ArrayDataSet;

class DBTest extends DbWebTestCase 
{
    /**
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    public function getConnection()
    {
        $pdo = new PDO("mysql:dbname=softpro;host=localhost", 'softpro', 'softpro', array('1002' => "SET NAMES 'UTF8'"));
        return $this->createDefaultDBConnection($pdo, ':memory:');
    }

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet()
    {
        return new ArrayDataSet(array(
            'user' => array(
                array(
                    'id' => 1,
                    'username' => 'admin',
                    'email' => 'boxfrommars@gmail.com',
                    'password' => md5('myparis'),
                ),
            ),
            'post' => array(
                array(
                    'id' => 1,
                    'title' => 'first article',
                    'content' => '<p>Думал ли я, что однажды смогу вот так, положа руку на сердце, рассказать всю мою жизнь? Кому-то, кто внимательно меня выслушает? Я счастлив в моем положении, которому, наверно, мало кто позавидует, ибо оно дает мне эту возможность, столь редкую в нашей жизни. Это неземное блаженство, это полет души, когда нет иных забот и занятий, кроме одного: говорить правду, свободно и не стесняясь, всю правду как она есть.</p><p>Случай мой диковинный.</p>',
                    'id_user' => 1, 
                    'updated_at' => '2012-01-01 17:15:23',
                    'created_at' => '2012-01-01 17:15:23'
                ),
            ),
        ));
    }
    
    public function createApplication()
    {
        require __DIR__ . '/../../application/app.php';
        return $app;
    }

    public function testTest()
    {
        $this->assertTrue(true);
    }
    
    public function testPostInsert()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('post'));
        $count = $this->app['post.dbtable']->insert(array(
            'title' => 'second article',
            'id_user' => 1,
            'content' => 'Очередная статья на подходе',
            'created_at' => null,
            'updated_at' => null,
        ));
        
        $this->assertEquals(1, $count, "After-Condition");
        $this->assertEquals(2, $this->getConnection()->getRowCount('post'));
    }
    
    public function testPostUpdate()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('post'));
        
        $newTitle = 'first article edited';
        $postId = 1;
        
        $count = $this->app['post.dbtable']->update(array('title' => $newTitle), $postId);
        $this->assertEquals(1, $count);
        
        $insertedPost = $this->app['post.dbtable']->get($postId);
        $this->assertEquals($newTitle, $insertedPost['title']);
    }
    
    public function testPostDelete()
    {
        $postId = 1;
        $this->assertEquals(1, $this->getConnection()->getRowCount('post'));
        $count = $this->app['post.dbtable']->delete($postId);
        $this->assertEquals(1, $count);
        $this->assertEquals(0, $this->getConnection()->getRowCount('post'));
    }
}

?>
