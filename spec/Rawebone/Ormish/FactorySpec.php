<?php

namespace spec\Rawebone\Ormish;

use Rawebone\Ormish\Populator;
use Rawebone\Ormish\SqlGeneratorInterface;
use Psr\Log\LoggerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FactorySpec extends ObjectBehavior
{
    function let()
    {
        $dsn = "sqlite::memory:";
        $username = "";
        $password = "";
        $options  = array();
        
        $this->beConstructedWith($dsn, $username, $password, $options);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Factory');
    }
    
    function it_should_build()
    {
        $this->build()->shouldReturnAnInstanceOf('Rawebone\Ormish\Database');
    }
    
    function it_should_use_a_custom_logger(LoggerInterface $log)
    {
        $this->setLogger($log);
        $this->logger()->shouldBe($log);
    }
    
    function it_should_use_a_custom_generator(SqlGeneratorInterface $gen)
    {
        $this->setGenerator($gen);
        $this->generator()->shouldBe($gen);
    }
    
    function it_should_use_a_custom_populator(Populator $pop)
    {
        $this->setPopulator($pop);
        $this->populator()->shouldBe($pop);
    }
    
    function it_should_use_a_custom_executor()
    {
        $this->setExecutorName('Non\ExistantClass');
        $this->shouldThrow('ReflectionException')->during("build");
    }
}
