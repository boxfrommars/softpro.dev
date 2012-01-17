<?php
namespace Softpro;

use Symfony\Component\HttpKernel\Client;
use Symfony\Component\HttpKernel\Test\WebTestCase as BaseWebTestCase;

/**
 * Silex не предоставляет класса для тестирования DB (WebTestCase наследуется от PHPUnit_FrameworkTestCase),
 * немного телодвижений и он становится пригодным для этого
 *
 * @category Softpro
 * @package  Test
 * @author   Dmitry Groza <boxfrommars@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://github.com/boxfrommars/softpro.dev
 */
abstract class DbWebTestCase extends \PHPUnit_Extensions_Database_TestCase
{
    protected $_app;

    /**
     * PHPUnit setUp for setting up the application.
     *
     * Note: Child classes that define a setUp method must call
     * parent::setUp().
     *
     * @return null
     */
    public function setUp()
    {
        parent::setUp();
        $this->_app = $this->createApplication();
    }

    /**
     * end
     *
     * @return null
     */
    public function tearDown()
    {
        parent::setUp();
        parent::tearDown();
    }

    /**
     * Creates the application.
     *
     * @return Symfony\Component\HttpKernel\HttpKernel
     */
    abstract public function createApplication();

    /**
     * Creates a Client.
     *
     * @param array $options An array of options to pass to the createKernel class
     * @param array $server  An array of server parameters
     *
     * @return Client A Client instance
     */
    public function createClient(array $options = array(), array $server = array())
    {
        return new Client($this->_app);
    }
}
