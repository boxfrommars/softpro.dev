<?php

namespace Softpro\Blog;

/**
 * comment service
 *
 * @category Softpro
 * @package  Blog
 * @author   Dmitry Groza <boxfrommars@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://github.com/boxfrommars/softpro.dev
 */
class CommentService
{

    /**
     * @var CommentDbTableInterface
     */
    protected $_dbTable;

    /**
     * set comments db table
     *
     * @param CommentDbTableInterface $dbTable db table
     *
     * @return CommentService
     */
    public function setDbTable(CommentDbTableInterface $dbTable)
    {
        $this->_dbTable = $dbTable;
        return $this;
    }

    /**
     * get comment by id
     *
     * @param int $id comment id
     *
     * @return Comment
     */
    public function get($id)
    {
        $raw = $this->_dbTable->get($id);
        if ($raw) return new Comment($raw);
        return null;
    }

    /**
     * get array of Comment
     *
     * @return Comment[]
     */
    public function getList()
    {
        $listRaw = $this->_dbTable->getList();
        $list = array();
        foreach ($listRaw as $raw) $list[] = new Comment($raw);
        return $list;
    }

    /**
     * get comments by postId
     *
     * @param int $postId post id
     *
     * @return Comment[]
     */
    public function getPostComments($postId)
    {
        $listRaw = $this->_dbTable->getList(array('postId' => $postId));
        $list = array();
        foreach ($listRaw as $raw) $list[] = new Comment($raw);
        return $list;
    }

    /**
     * save comment
     *
     * @param Comment $comment comment to save
     *
     * @return int count of updated/inserted rows (1 or 0)
     */
    public function save(Comment $comment)
    {
        $id = $comment->getId();

        if ($this->isValid($comment)) {
            if (empty($id) || !($this->get($id) instanceof Comment)) {
                $comment->setId(null);
                $count = $this->_dbTable->insert($comment->getRaw());
            } else {
                $count = $this->_dbTable->update($comment->getRaw(), $id);
            }
        } else {
            throw new \Softpro\InvalidItemException('Invalid Comment');
        }

        return $count;
    }

    /**
     * delete comment with given id
     *
     * @param int $id comment id
     *
     * @return int count of deleted rows (1 or 0)
     */
    public function delete($id)
    {
        return $this->_dbTable->delete($id);
    }

    /**
     * check is comment valid
     *
     * @param Comment $comment comment to delete
     *
     * @return bool
     */
    public function isValid(Comment $comment)
    {
        $valid = true;
        $valid &= ($comment->getAuthorName() && $comment->getAuthorEmail());
        $valid &= ($comment->getPostId());

        return $valid;
    }

    /**
     * check is comment with given id exist
     *
     * @param type $id comment id
     *
     * @return bool
     */
    public function isExist($id)
    {
        $comment = $this->get($id);
        return !empty($comment);
    }
}