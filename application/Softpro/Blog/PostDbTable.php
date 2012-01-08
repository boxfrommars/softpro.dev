<?php

namespace Softpro\Blog;

class PostDbTable implements PostDbTableInterface {

    /**
     * @var \Doctrine\DBAL\Connection 
     */
    protected $_db;
    protected $_map = array(
        'id' => 'id',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
        'authorId' => 'id_user',
        'title' => 'title',
        'content' => 'content',
    );
    
    /**
     * @param \Doctrine\DBAL\Connection $db 
     */
    public function setDb($db)
    {
        $this->_db = $db;
    }
    
    protected function _getBaseSelectSql()
    {
        $fieldsPart = '';
        foreach ($this->_map as $key => $dbKey) {
            $fieldsPart .= "post.{$dbKey} AS {$key}, \n"; 
        }
        $fieldsPart .= "author.username AS authorName \n";
        
        return 'SELECT ' . $fieldsPart . 'FROM post LEFT JOIN user AS author ON post.id_user = author.id';
    }
    
    public function get($id)
    {
        $sql = $this->_getBaseSelectSql() . ' WHERE post.id = ?';
        return $this->_db->fetchAssoc($sql, array($id));
    }
    
    public function insert($data)
    {
        return $this->_db->insert('post', $this->_getMappedData($data));
    }
    
    public function update($data, $id)
    {
        return $this->_db->update('post', $this->_getMappedData($data), array('id' => $id));
    }
    
    public function delete($id)
    {
        return $this->_db->delete('post', array('id' => $id));
    }
    
    public function getList()
    {
        $sql = $this->_getBaseSelectSql() . ' ORDER BY created_at DESC LIMIT 10';
        return $this->_db->fetchAll($sql);
    }
    
    protected function _getMappedData($data)
    {
        $mappedData = array();
        foreach ($data as $key => $value) {
            $realKey = !empty($this->_map[$key]) ? $this->_map[$key] : null;
            if (null !== $realKey) $mappedData[$realKey] = $value;
        }
        return $mappedData;
    }
}