<?php
namespace Softpro\Blog;

class Comment {
    protected $_raw = array(
        'id' => null,
        'postId' => null,
        'authorId' => null,
        'authorName' => null,
        'authorEmail' => null,
        'content' => null,
        'updatedAt' => null,
        'createdAt' => null
    );

    public function __construct($raw) {
        foreach ($raw as $key => $value) {
            if (array_key_exists($key, $this->_raw)) $this->_raw[$key] = $value;
        }
    }
    
    public function __call($name, $arguments) {
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
    
    public function getRaw()
    {
        return $this->_raw;
    }
}
