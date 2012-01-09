<?php
namespace Softpro\Blog;

class CommentService {
    
    /**
     * @var CommentDbTableInterface
     */
    protected $_dbTable;
    
    public function setDbTable(CommentDbTableInterface $dbTable)
    {
        $this->_dbTable = $dbTable;
    }
    
    /**
     * @param int $id
     * @return Comment 
     */
    public function get($id)
    {
        $raw = $this->_dbTable->get($id);
        if ($raw) return new Comment($raw);
        return null;
    }
    
    /**
     * @return array of Comment
     */
    public function getList()
    {
        $listRaw = $this->_dbTable->getList();
        $list = array();
        foreach ($listRaw as $raw) $list[] = new Comment($raw);
        return $list;
    }
    
    
    public function getPostComments($postId)
    {
        $listRaw = $this->_dbTable->getList(array('postId' => $postId));
        $list = array();
        foreach ($listRaw as $raw) $list[] = new Comment($raw);
        return $list;
    }
    
    /**
     * @param Comment $comment
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
    
    public function delete($id)
    {
        return $this->_dbTable->delete($id);
    }
    
    public function isValid(Comment $comment)
    {
        $valid = true;
        $valid &= ($comment->getAuthorName() && $comment->getAuthorEmail());
        $valid &= ($comment->getPostId());
        
        return $valid;
    }
    
    public function isExist($id)
    {
        $comment = $this->get($id);
        return !empty($comment);
    }
}