<?php
namespace Rawebone\Ormish\Tests;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * The current prophet instance.
     *
     * @var \Prophecy\Prophet
     */
    protected $prophet;
    
    /**
     * The in-memory database to be used for testing.
     *
     * @var \PDO
     */
    protected $conn;
    
    public function setUp()
    {
        $this->prophet = $this->getProphet();
        $this->conn = $this->getMemoryConn();
    }
    
    public function tearDown()
    {
        $this->prophet->checkPredictions();
    }
    
    /**
     * @return \Prophecy\Prophet
     */
    protected function getProphet()
    {
        return new \Prophecy\Prophet;
    }

    /**
     * @return \PDO
     */
    protected function getMemoryConn()
    {
        return new \PDO("sqlite::memory:");
    }
}
