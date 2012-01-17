<?php

namespace Softpro\Blog;

/**
 * Db table interface for posts
 *
 * @category Softpro
 * @package  Blog
 * @author   Dmitry Groza <boxfrommars@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://github.com/boxfrommars/softpro.dev
 */
interface PostDbTableInterface
{

    /**
     * get item from db by id
     *
     * @param int $id id of item
     *
     * @return array
     */
    public function get($id);

    /**
     * get array of raws
     *
     * @return array of raws
     */
    public function getList();

    /**
     * set db connection
     *
     * @param \Doctrine\DBAL\Connection $db connection
     *
     * @return PostDbTable
     */
    public function setDb($db);

    /**
     * delete raw from db with $id
     *
     * @param int $id raw id
     *
     * @return int number of deleted raw (1 or 0)
     */
    public function delete($id);

    /**
     * accept Item raw $data, map into array needed by db and update raw with $id
     *
     * @param array $data array, wich keys are the same as in $this->_map
     * @param int   $id   id of updated raw
     *
     * @return int number of updated raws
     */
    public function update($data, $id);

    /**
     * accept Item raw $data, map into array needed by db and insert
     *
     * @param array $data array, wich keys are the same as in $this->_map
     *
     * @return int number of inserted raws
     */
    public function insert($data);

}