<?php

namespace Softpro\Blog;

class CommentDbTable implements CommentDbTableInterface {

    /**
     * @var \Doctrine\DBAL\Connection 
     */
    protected $_db;
    protected $_tableName = 'comment';
    protected $_map = array(
        'id' => 'id',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
        'authorId' => 'id_user',
        'authorName' => 'name',
        'authorEmail' => 'email',
        'postId' => 'id_post',
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
        $fieldsPart = array();
        foreach ($this->_map as $key => $dbKey) {
            $fieldsPart[] = "{$this->_getTableName()}.{$dbKey} AS {$key}"; 
        }
        
        return 'SELECT ' . implode(",\n", $fieldsPart) . ' FROM ' . $this->_getTableName();
    }
    
    public function get($id)
    {
        $sql = $this->_getBaseSelectSql() . ' WHERE ' . $this->_getTableName() . '.id = ?';
        return $this->_db->fetchAssoc($sql, array($id));
    }
    
    public function insert($data)
    {
        return $this->_db->insert($this->_getTableName(), $this->_getMappedData($data));
    }
    
    public function update($data, $id)
    {
        return $this->_db->update($this->_getTableName(), $this->_getMappedData($data), array('id' => $id));
    }
    
    public function delete($id)
    {
        return $this->_db->delete($this->_getTableName(), array('id' => $id));
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
    
    protected function _getTableName()
    {
        return $this->_tableName;
    }
}