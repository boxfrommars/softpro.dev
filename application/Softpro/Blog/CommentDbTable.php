<?php

namespace Softpro\Blog;

/**
 * Db table for comments
 *
 * @category Softpro
 * @package  Blog
 * @author   Dmitry Groza <boxfrommars@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://github.com/boxfrommars/softpro.dev
 */
class CommentDbTable implements CommentDbTableInterface
{

    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $_db;

    /**
     * @var string items tablename
     */
    protected $_tableName = 'comment';

    /**
     * @var array map between Camment raw and db format
     */
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
     * set db connection
     *
     * @param \Doctrine\DBAL\Connection $db connection
     *
     * @return CommentDbTable
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
        $fieldsPart = array();
        foreach ($this->_map as $key => $dbKey) {
            $fieldsPart[] = "{$this->_getTableName()}.{$dbKey} AS {$key}";
        }

        return 'SELECT ' . implode(",\n", $fieldsPart) . ' FROM ' . $this->_getTableName();
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
        $sql = $this->_getBaseSelectSql() . ' WHERE ' . $this->_getTableName() . '.id = ?';
        return $this->_db->fetchAssoc($sql, array($id));
    }

    /**
     * accept Comment raw $data, map into array needed by db and insert
     *
     * @param array $data array, wich keys are the same as in $this->_map
     *
     * @return int number of inserted raws
     */
    public function insert($data)
    {
        return $this->_db->insert($this->_getTableName(), $this->_getMappedData($data));
    }

    /**
     * accept Comment raw $data, map into array needed by db and update raw with $id
     *
     * @param array $data array, wich keys are the same as in $this->_map
     * @param int   $id   id of updated raw
     *
     * @return int number of updated raws
     */
    public function update($data, $id)
    {
        return $this->_db->update($this->_getTableName(), $this->_getMappedData($data), array('id' => $id));
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
        return $this->_db->delete($this->_getTableName(), array('id' => $id));
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
     * map data by Comment raw to db format
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