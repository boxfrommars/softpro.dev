<?php
namespace Softpro;

/**
 * реализация DataSet на массивах (пока не идёт из коробки, но в доках есть класс (ниже и представлен) для копипасты)
 *
 * @category Softpro
 * @package  Test
 * @author   Dmitry Groza <boxfrommars@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://github.com/boxfrommars/softpro.dev
 */
class ArrayDataSet extends \PHPUnit_Extensions_Database_DataSet_AbstractDataSet
{
    /**
     * @var array
     */
    protected $_tables = array();

    /**
     * constructor
     *
     * @param array $data data
     */
    public function __construct(array $data)
    {
        foreach ($data AS $tableName => $rows) {
            $columns = array();
            if (isset($rows[0])) {
                $columns = array_keys($rows[0]);
            }

            $metaData = new \PHPUnit_Extensions_Database_DataSet_DefaultTableMetaData($tableName, $columns);
            $table = new \PHPUnit_Extensions_Database_DataSet_DefaultTable($metaData);

            foreach ($rows AS $row) {
                $table->addRow($row);
            }
            $this->_tables[$tableName] = $table;
        }
    }

    /**
     * create iterator
     *
     * @param bool $reverse is reversed
     *
     * @return \PHPUnit_Extensions_Database_DataSet_DefaultTableIterator
     */
    protected function createIterator($reverse = false)
    {
        return new \PHPUnit_Extensions_Database_DataSet_DefaultTableIterator($this->_tables, $reverse);
    }

    /**
     * get table
     *
     * @param string $tableName table name
     *
     * @return \PHPUnit_Extensions_Database_DataSet_DefaultTable
     */
    public function getTable($tableName)
    {
        if (!isset($this->_tables[$tableName])) {
            throw new InvalidArgumentException("$tableName is not a table in the current database.");
        }

        return $this->_tables[$tableName];
    }
}