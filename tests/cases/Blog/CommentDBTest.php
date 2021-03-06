<?php

use Softpro\DbWebTestCase;
use Softpro\ArrayDataSet;

class BlogCommentDBTest extends DbWebTestCase
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

    public function testCommentGet()
    {
        $existedComment = array(
                    'id' => 1,
                    'postId' => 1,
                    'authorId' => 1,
                    'authorEmail' => 'boxfrommars@gmail.com',
                    'authorName' => 'boxfrommars',

                    'content' => 'very useful',
                    'updatedAt' => '2012-01-01 19:15:23',
                    'createdAt' => '2012-01-01 19:15:23'
                );


        $nonExistCommentId = 1000;

        $comment = $this->_app['comment.dbtable']->get($existedComment['id']);
        $this->assertTrue(!empty ($comment));
        $this->assertEquals($comment, $existedComment);

        $comment = $this->_app['post.dbtable']->get($nonExistCommentId);
        $this->assertTrue(empty ($comment));
    }

    public function testCommentInsert()
    {
        $beforeCount = $this->getConnection()->getRowCount('comment');
        $toInsert = array(
            'authorId' => 1,
            'authorName' => 'boxfrommars',
            'authorEmail' => 'boxfrommars@gmail.com',
            'postId' => 1,
            'content' => 'Очередная статья на подходе',

            'createdAt' => null,
            'updatedAt' => null,
        );
        $count = $this->_app['comment.dbtable']->insert($toInsert);

        $this->assertEquals(1, $count);
        $this->assertEquals($beforeCount + 1, $this->getConnection()->getRowCount('comment'));

        $insertedId = $this->_app['db']->lastInsertId();
        $insertedItem = $this->_app['comment.dbtable']->get($insertedId);

        $compareBy = array('authorId', 'authorName', 'authorEmail', 'postId', 'content');
        $this->assertEquals(array_intersect_key($toInsert, $compareBy), array_intersect_key($insertedItem, $compareBy));
    }

    /**
     * @dataProvider commentUpdateProvider
     */
    public function testCommentUpdate($id, $data)
    {
        $originalItem = $this->_app['comment.dbtable']->get($id);

        $count = $this->_app['comment.dbtable']->update($data, $id);
        if (!empty($originalItem)) {
            $this->assertEquals(1, $count);
            $editedItem = $this->_app['comment.dbtable']->get($id);
            $this->assertEquals($data, array_intersect_key($editedItem, $data));
        } else {
            $this->assertEquals(0, $count);
        }
    }

    public function commentUpdateProvider()
    {
        return array(
            array(1, array('content' => 'edited comment')),
            array(1000, array('content' => 'edited comment')),
        );
    }

    /**
     * @dataProvider commentDeleteProvider
     */
    public function testCommentDelete($id)
    {
        $beforeCount = $this->getConnection()->getRowCount('comment');
        $originalItem = $this->_app['comment.dbtable']->get($id);

        $count = $this->_app['comment.dbtable']->delete($id);
        if (!empty($originalItem)) {
            $this->assertEquals(1, $count);
            $this->assertEquals($beforeCount - 1, $this->getConnection()->getRowCount('comment'));
        } else {
            $this->assertEquals(0, $count);
        }
    }

    public function commentDeleteProvider()
    {
        return array(
            array(1),
            array(2),
            array(1000),
        );
    }
}