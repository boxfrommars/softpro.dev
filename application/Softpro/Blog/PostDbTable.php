<?php

namespace Softpro\Blog;

class PostDbTable implements PostDbTableInterface {

    /**
     * @var \Doctrine\DBAL\Connection 
     */
    protected $_db;
    
    /**
     * @param \Doctrine\DBAL\Connection $db 
     */
    public function setDb($db)
    {
        $this->_db = $db;
    }
    
    public function get($id)
    {
        $sql = "SELECT * FROM post WHERE id = ?";
        return $this->_db->fetchAssoc($sql, array($id));
    }
    
    public function insert($data)
    {
        return $this->_db->insert('post', $data);
    }
    
    public function update($data, $id)
    {
        return $this->_db->update('post', $data, array('id' => $id));
    }
    
    public function delete($id)
    {
        return $this->_db->delete('post', array('id' => $id));
    }
    
    public function getList()
    {
        $sql = 'SELECT * FROM post ORDER BY created_at LIMIT 10';
        return $this->_db->fetchAll($sql);
    }
}