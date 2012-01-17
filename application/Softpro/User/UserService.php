<?php

namespace Softpro\User;

/**
 * post service
 *
 * @category Softpro
 * @package  Blog
 * @author   Dmitry Groza <boxfrommars@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://github.com/boxfrommars/softpro.dev
 */
class UserService
{

    /**
     * @var PostDbTableInterface
     */
    protected $_dbTable;

    /**
     * set users db table
     *
     * @param UserDbTable $dbTable db table
     *
     * @return UserService
     */
    public function setDbTable(UserDbTable $dbTable)
    {
        $this->_dbTable = $dbTable;
        return $this;
    }

    /**
     * get user by id
     *
     * @param int $id user id
     *
     * @return User
     */
    public function get($id)
    {
        $raw = $this->_dbTable->get($id);
        if ($raw) return new User($raw);
        return null;
    }

    /**
     * get array of User
     *
     * @return User[]
     */
    public function getList()
    {
        $listRaw = $this->_dbTable->getList();
        $list = array();
        foreach ($listRaw as $raw) $list[] = new User($raw);
        return $list;
    }

    /**
     * save user
     *
     * @param User $user post to save
     *
     * @return int count of updated/inserted rows (1 or 0)
     */
    public function save(User $user)
    {
        $id = $user->getId();
        if (empty($id)) {
            $count = $this->_dbTable->insert($user->getRaw());
        } else {
            $count = $this->_dbTable->update($user->getRaw(), $id);
        }
        return $count;
    }

    /**
     * delete user with given id
     *
     * @param int $id user id
     *
     * @return int count of deleted rows (1 or 0)
     */
    public function delete($id)
    {
        return $this->_dbTable->delete($id);
    }
}