<?php

namespace spec\Rawebone\Ormish;

use PDO;
use PDOStatement;
use Psr\Log\LoggerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExecutorSpec extends ObjectBehavior
{
    function let(PDO $pdo, LoggerInterface $log, PDOStatement $stmt)
    {
        $this->beConstructedWith($pdo, $log);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Executor');
    }
    
    function it_should_execute_a_query($pdo, $log, $stmt)
    {
        $query  = "SELECT * FROM whatever";
        $params = array(
            "whatever" => 1,
            "aardvark" => "beta"
        );
        
        $pdo->prepare($query)->willReturn($stmt);
        $stmt->execute($params)->willReturn(true);
        
        $log->info("Successful Query: SELECT * FROM whatever [Params: whatever = 1, aardvark = beta]")
            ->shouldBeCalled();
        
        $this->query($query, $params)->shouldReturn($stmt);
    }
    
    function it_should_fail_to_execute_a_query($pdo, $log, $stmt)
    {
        $query  = "SELECT * FROM whatever";
        $params = array();
        
        $pdo->prepare($query)->willReturn($stmt);
        $pdo->errorInfo()->willReturn(array("ABC12", 1, "Message"));
        $stmt->execute($params)->willReturn(false);
        
        $log->error("Failed Query: SELECT * FROM whatever [Params: ]; Error: ABC12 Message (1)")
            ->shouldBeCalled();
        
        $err = $this->query($query, $params);
        $err->shouldReturnAnInstanceOf('Rawebone\Ormish\Error');
        $err->code()->shouldReturn("ABC12");
        $err->message()->shouldReturn("Message (1)");
        $err->query()->shouldReturn("SELECT * FROM whatever");
        $err->params()->shouldReturn(array());
    }
    
    function it_should_execute_a_statement($pdo, $log, $stmt)
    {
        $query  = "INSERT INTO boot () VALUES()";
        $params = array();
        
        $pdo->prepare($query)->willReturn($stmt);
        $stmt->execute($params)->willReturn(true);
        
        $log->info("Successful Query: INSERT INTO boot () VALUES() [Params: ]")
            ->shouldBeCalled();
        
        $this->exec($query, $params)->shouldReturn(true);
    }
    
    function it_should_fail_to_execute_a_statement($pdo, $log, $stmt)
    {
        $query  = "INSERT INTO boot () VALUES()";
        $params = array();
        
        $pdo->prepare($query)->willReturn($stmt);
        $pdo->errorInfo()->willReturn(array("ABC12", 1, "Message"));
        $stmt->execute($params)->willReturn(false);
        
        $log->error("Failed Query: INSERT INTO boot () VALUES() [Params: ]; Error: ABC12 Message (1)")
            ->shouldBeCalled();
        
        $err = $this->exec($query, $params);
        $err->shouldBeAnInstanceOf('Rawebone\Ormish\Error');
        $err->code()->shouldReturn("ABC12");
        $err->message()->shouldReturn("Message (1)");
        $err->query()->shouldReturn("INSERT INTO boot () VALUES()");
        $err->params()->shouldReturn(array());
    }
    
    function it_should_return_a_pdo_object()
    {
        $this->connection()->shouldReturnAnInstanceOf('PDO');
    }
    
    function it_should_marshall_call_to_lastInsertId($pdo)
    {
        $pdo->lastInsertId()
            ->willReturn(1)
            ->shouldBeCalled();
        
        $this->lastInsertId()->shouldReturn(1);
    }
    
    /**
     * @param \PDO $pdo
     */
    function it_should_marshall_calls_for_transactions($pdo)
    {
        $pdo->beginTransaction()->shouldBeCalled();
        $this->beginTransaction();
        
        $pdo->commit()->shouldBeCalled();
        $this->commit();
        
        $pdo->rollBack()->shouldBeCalled();
        $this->rollback();
    }
}
