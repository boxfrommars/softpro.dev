<?php

namespace Softpro\Blog;

/**
 * post service
 *
 * @category Softpro
 * @package  Blog
 * @author   Dmitry Groza <boxfrommars@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://github.com/boxfrommars/softpro.dev
 */
class PostService
{

    /**
     * @var PostDbTableInterface
     */
    protected $_dbTable;

    /**
     * set posts db table
     *
     * @param PostDbTableInterface $dbTable db table
     *
     * @return PostService
     */
    public function setDbTable(PostDbTableInterface $dbTable)
    {
        $this->_dbTable = $dbTable;
        return $this;
    }

    /**
     * get post by id
     *
     * @param int $id post id
     *
     * @return Post
     */
    public function get($id)
    {
        $postRaw = $this->_dbTable->get($id);
        if ($postRaw) return new Post($postRaw);
        return null;
    }

    /**
     * get array of Post
     *
     * @return Post[]
     */
    public function getList()
    {
        $listRaw = $this->_dbTable->getList();
        $list = array();
        foreach ($listRaw as $postRaw) $list[] = new Post($postRaw);
        return $list;
    }

    /**
     * save post
     *
     * @param Post $post post to save
     *
     * @return int count of updated/inserted rows (1 or 0)
     */
    public function save(Post $post)
    {
        $id = $post->getId();
        if (empty($id)) {
            $count = $this->_dbTable->insert($post->getRaw());
        } else {
            $count = $this->_dbTable->update($post->getRaw(), $id);
        }
        return $count;
    }

    /**
     * delete post with given id
     *
     * @param int $id post id
     *
     * @return int count of deleted rows (1 or 0)
     */
    public function delete($id)
    {
        return $this->_dbTable->delete($id);
    }
}