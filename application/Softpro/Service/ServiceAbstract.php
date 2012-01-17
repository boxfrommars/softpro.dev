<?php

namespace Softpro\Service;

/**
 * service
 *
 * @category Softpro
 * @package  Service
 * @author   Dmitry Groza <boxfrommars@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://github.com/boxfrommars/softpro.dev
 */
abstract class DbServiceAbstract
{
    /**
     * get item by id
     *
     * @param mixed $id item id
     * 
     * @return obj
     */
    abstract public function get($id)
    {

    }
}
