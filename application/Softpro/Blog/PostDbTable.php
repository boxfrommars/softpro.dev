<?php

namespace Softpro\Blog;

/**
 * Db table for posts
 *
 * @category Softpro
 * @package  Blog
 * @author   Dmitry Groza <boxfrommars@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://github.com/boxfrommars/softpro.dev
 */
class PostDbTable implements PostDbTableInterface
{

    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $_db;

    /**
     * @var array map between Item raw and db format
     */
    protected $_map = array(
        'id' => 'id',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
        'authorId' => 'id_user',
        'title' => 'title',
        'content' => 'content',
    );

    /**
     * set db connection
     *
     * @param \Doctrine\DBAL\Connection $db connection
     *
     * @return PostDbTable
     */
    public function setDb($db)
    {
        $this->_db = $db;
        return $this;
    }

    /**
     * sql string for build needed query
     *
     * @return string
     */
    protected function _getBaseSelectSql()
    {
        $fieldsPart = '';
        foreach ($this->_map as $key => $dbKey) {
            $fieldsPart .= "post.{$dbKey} AS {$key}, \n";
        }
        $fieldsPart .= "author.username AS authorName \n";

        return 'SELECT ' . $fieldsPart . 'FROM post LEFT JOIN user AS author ON post.id_user = author.id';
    }

    /**
     * get item from db by id
     *
     * @param int $id id of item
     *
     * @return array
     */
    public function get($id)
    {
        $sql = $this->_getBaseSelectSql() . ' WHERE post.id = ?';
        return $this->_db->fetchAssoc($sql, array($id));
    }

    /**
     * accept Item raw $data, map into array needed by db and insert
     *
     * @param array $data array, wich keys are the same as in $this->_map
     *
     * @return int number of inserted raws
     */
    public function insert($data)
    {
        return $this->_db->insert('post', $this->_getMappedData($data));
    }

    /**
     * accept Item raw $data, map into array needed by db and update raw with $id
     *
     * @param array $data array, wich keys are the same as in $this->_map
     * @param int   $id   id of updated raw
     *
     * @return int number of updated raws
     */
    public function update($data, $id)
    {
        return $this->_db->update('post', $this->_getMappedData($data), array('id' => $id));
    }

    /**
     * delete raw from db with $id
     *
     * @param int $id raw id
     *
     * @return int number of deleted raw (1 or 0)
     */
    public function delete($id)
    {
        return $this->_db->delete('post', array('id' => $id));
    }

    /**
     * get array of raws
     *
     * @return array of raws
     */
    public function getList()
    {
        $sql = $this->_getBaseSelectSql() . ' ORDER BY created_at DESC LIMIT 10';
        return $this->_db->fetchAll($sql);
    }

    /**
     * map data by Item raw to db format
     *
     * @param array $data array, wich keys are the same as in $this->_map
     *
     * @return array
     */
    protected function _getMappedData($data)
    {
        $mappedData = array();
        foreach ($data as $key => $value) {
            $realKey = !empty($this->_map[$key]) ? $this->_map[$key] : null;
            if (null !== $realKey) $mappedData[$realKey] = $value;
        }
        return $mappedData;
    }

    /**
     * get items table name
     *
     * @return string
     */
    protected function _getTableName()
    {
        return $this->_tableName;
    }
}