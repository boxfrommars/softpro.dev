<?php

namespace Softpro\User;

/**
 * post entity
 *
 * @method getId() int
 * @method getEmail() int
 * @method getUsername() int
 *
 * @method setId() int
 * @method setEmail() int
 * @method setUsername() int
 *
 * @method hasId() int
 * @method hasEmail() int
 * @method hasUsername() int
 *
 * @category Softpro
 * @package  User
 * @author   Dmitry Groza <boxfrommars@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://github.com/boxfrommars/softpro.dev
 */
class User
{

    /**
     * object data
     *
     * @var array raw data of object
     */
    protected $_raw = array(
        'id' => null,
        'email' => null,
        'username' => null,
    );

    /**
     * set user params
     *
     * @param array $raw array of raw params
     */
    public function __construct($raw)
    {
        // set options exists in $this->_raw array
        foreach ($raw as $key => $value) {
            if (array_key_exists($key, $this->_raw)) $this->_raw[$key] = $value;
        }
    }

    /**
     * magic for getSmth, setSmth, hasSmth
     *
     * @param string $name      property name
     * @param array  $arguments arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $action = substr($name, 0, 3);

        switch ($action) {
            case 'get':
                $key = lcfirst(substr($name, 3));
                if (array_key_exists($key, $this->_raw)) return $this->_raw[$key];
                throw new \Softpro\InvalidPropertyException('Call to undefined method ' . $name);
                break;

            case 'set':
                $key = lcfirst(substr($name, 3));
                if (array_key_exists($key, $this->_raw)) {
                    $this->_raw[$key] = $arguments[0];
                } else {
                    throw new \Softpro\InvalidPropertyException('Call to undefined method ' . $name);
                }
                break;

            case 'has':
                $key = lcfirst(substr($name, 3));
                return !empty($this->_raw[$key]);
                break;

            default:
                throw new \Softpro\InvalidPropertyException('Call to undefined method ' . $name);
        }
    }

    /**
     * get serialized user
     *
     * @return array user object to array
     */
    public function getRaw()
    {
        return $this->_raw;
    }
}
