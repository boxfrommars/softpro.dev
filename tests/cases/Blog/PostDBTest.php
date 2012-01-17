<?php

use Softpro\DbWebTestCase;
use Softpro\ArrayDataSet;

class BlogPostDBTest extends DbWebTestCase
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
                array(
                    'id' => 2,
                    'title' => 'second article',
                    'content' => '<p>From the previous example it isnt obvious how you would specify an empty table. You can insert a tag with no attributes with the name of the empty table. A flat xml file for an empty guestbook table would then look like:</p>',
                    'id_user' => 1,
                    'updated_at' => '2012-01-01 18:15:23',
                    'created_at' => '2012-01-01 18:15:23'
                ),
            ),
            'comment' => array(
                array(
                    'id' => 1,
                    'id_post' => 1,
                    'id_user' => 1,
                    'email' => 'boxfrommars@gmail.com',
                    'name' => 'boxfrommars',

                    'content' => 'very useful',
                    'updated_at' => '2012-01-01 19:15:23',
                    'created_at' => '2012-01-01 19:15:23'
                ),
            )
        ));
    }

    public function createApplication()
    {
        require __DIR__ . '/../../../application/app.php';
        return $app;
    }

    public function testPostGet()
    {
        $existedPost = array(
                    'id' => 1,
                    'title' => 'first article',
                    'content' => '<p>Думал ли я, что однажды смогу вот так, положа руку на сердце, рассказать всю мою жизнь? Кому-то, кто внимательно меня выслушает? Я счастлив в моем положении, которому, наверно, мало кто позавидует, ибо оно дает мне эту возможность, столь редкую в нашей жизни. Это неземное блаженство, это полет души, когда нет иных забот и занятий, кроме одного: говорить правду, свободно и не стесняясь, всю правду как она есть.</p><p>Случай мой диковинный.</p>',
                    'authorId' => 1,
                    'authorName' => 'admin',
                    'updatedAt' => '2012-01-01 17:15:23',
                    'createdAt' => '2012-01-01 17:15:23'
                );


        $nonExistPostId = 1000;

        $post = $this->_app['post.dbtable']->get($existedPost['id']);
        $this->assertTrue(!empty ($post));
        $this->assertEquals($post, $existedPost);

        $post = $this->_app['post.dbtable']->get($nonExistPostId);
        $this->assertTrue(empty ($post));
    }

    public function testPostGetList()
    {
        $postsCount = $this->getConnection()->getRowCount('post');
        $postList = $this->_app['post.dbtable']->getList();

        $this->assertEquals($postsCount, count($postList));

        foreach ($postList as $post) {
            $this->assertEquals($this->_app['post.dbtable']->get($post['id']), $post);
        }
    }

    public function testPostInsert()
    {
        $postsCount = $this->getConnection()->getRowCount('post');
        $toInsert = array(
            'title' => 'second article',
            'authorId' => 1,
            'content' => 'Очередная статья на подходе',
            'createdAt' => null,
            'updatedAt' => null,
        );
        $count = $this->_app['post.dbtable']->insert($toInsert);


        $this->assertEquals(1, $count);
        $this->assertEquals($postsCount + 1, $this->getConnection()->getRowCount('post'));

        $id = $this->_app['db']->lastInsertId();
        $insertedItem = $this->_app['post.dbtable']->get($id);

        $compareBy = array('authorId', 'title', 'content');
        $this->assertEquals(array_intersect_key($toInsert, $compareBy), array_intersect_key($insertedItem, $compareBy));

    }

    /**
     * @dataProvider postUpdateProvider
     */
    public function testPostUpdate($id, $data)
    {
        $originalItem = $this->_app['post.dbtable']->get($id);

        $count = $this->_app['post.dbtable']->update($data, $id);
        if (!empty($originalItem)) {
            $this->assertEquals(1, $count);
            $editedItem = $this->_app['post.dbtable']->get($id);
            $this->assertEquals($data, array_intersect_key($editedItem, $data));
        } else {
            $this->assertEquals(0, $count);
        }
    }

    public function postUpdateProvider()
    {
        return array(
            array(1, array('title' => 'first article edited')),
            array(2, array('content' => 'second edited')),
            array(1000, array('content' => 'non existed edited')),
        );
    }

    /**
     * @dataProvider postDeleteProvider
     */
    public function testPostDelete($id)
    {
        $beforeCount = $this->getConnection()->getRowCount('post');
        $originalItem = $this->_app['post.dbtable']->get($id);

        $count = $this->_app['post.dbtable']->delete($id);
        if (!empty($originalItem)) {
            $this->assertEquals(1, $count);
            $this->assertEquals($beforeCount - 1, $this->getConnection()->getRowCount('post'));
        } else {
            $this->assertEquals(0, $count);
        }
    }

    public function postDeleteProvider()
    {
        return array(
            array(1),
            array(2),
            array(3),
            array(4),
            array(1000),
        );
    }
}