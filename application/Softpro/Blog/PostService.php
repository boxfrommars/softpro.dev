<?php
namespace Softpro\Blog;

class PostService {
    
    /**
     * @var PostDbTableInterface
     */
    protected $_dbTable;
    
    public function setDbTable(PostDbTableInterface $dbTable)
    {
        $this->_dbTable = $dbTable;
    }
    
    /**
     * @param int $id
     * @return Post 
     */
    public function get($id)
    {
        $postRaw = $this->_dbTable->get($id);
        if ($postRaw) return new Post($postRaw);
        return null;
    }
    
    /**
     * @return array of Post 
     */
    public function getList()
    {
        $listRaw = $this->_dbTable->getList();
        $list = array();
        foreach ($listRaw as $postRaw) $list[] = new Post($postRaw);
        return $list;
    }


    /**
     * @param Post $post
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
    
    public function delete($id)
    {
        return $this->_dbTable->delete($id);
    }
}