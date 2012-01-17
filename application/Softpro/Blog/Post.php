<?php

namespace Softpro\Blog;

/**
 * post entity
 *
 * @method getId() int
 * @method getContent() int
 * @method getAuthorId() int
 * @method getAuthorName() int
 * @method getContent() int
 * @method getUpdatedAt() int
 * @method getCreatedAt() int
 *
 * @method setId() int
 * @method getContent() int
 * @method setAuthorId() int
 * @method setAuthorName() int
 * @method setContent() int
 * @method setUpdatedAt() int
 * @method setCreatedAt() int
 *
 * @method hasId() int
 * @method getContent() int
 * @method hasAuthorId() int
 * @method hasAuthorName() int
 * @method hasContent() int
 * @method hasUpdatedAt() int
 * @method hasCreatedAt() int
 *
 * @category Softpro
 * @package  Blog
 * @author   Dmitry Groza <boxfrommars@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://github.com/boxfrommars/softpro.dev
 */
class Post
{

    /**
     * object data
     *
     * @var array raw data of object
     */
    protected $_raw = array(
        'id' => null,
        'title' => null,
        'authorId' => null,
        'authorName' => null,
        'content' => null,
        'updatedAt' => null,
        'createdAt' => null
    );

    /**
     * set post params
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
     * get serialized post
     *
     * @return array post object to array
     */
    public function getRaw()
    {
        return $this->_raw;
    }
}
