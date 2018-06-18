<?php
namespace Graze\Monolog\Handler;

use Mockery as m;
use Monolog\TestCase;

class WhoopsHandlerTest extends TestCase
{
    public function setUp()
    {
        if (!interface_exists('Whoops\Handler\HandlerInterface', true)) {
            $this->markTestSkipped('filp/whoops not installed');
        }
        
        $this->handlerWhoops = m::mock('Whoops\Handler\HandlerInterface');
    }

    public function testConstruct()
    {
        $this->assertInstanceOf('Graze\\Monolog\\Handler\\WhoopsHandler', new WhoopsHandler($this->handlerWhoops));
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Monolog\\Handler\\HandlerInterface', new WhoopsHandler($this->handlerWhoops));
    }

    public function testHandleError()
    {
        $record = $this->getRecord(300, 'test', ['file' => 'bar', 'line' => 1]);
        
        $formatter = m::mock('Monolog\\Formatter\\FormatterInterface');
        
        $handlerMonolog = new WhoopsHandler($this->handlerWhoops);
             
        $this->handlerWhoops
            ->shouldReceive('setInspector')
            ->once()
            ->with(m::type('Whoops\Exception\Inspector'));
              
        $this->handlerWhoops
            ->shouldReceive('setRun')
            ->once()
            ->with(m::type('Whoops\Run'));
               
        $this->handlerWhoops
            ->shouldReceive('setException')
            ->once()
            ->with(m::type('Whoops\Exception\ErrorException'));
             
        $this->handlerWhoops
            ->shouldReceive('handle')
            ->once();
             
        $handlerMonolog->handle($record);
    }

    public function testHandleException()
    {
        $exception = new \Whoops\Exception\ErrorException('foo');
        
        $record = $this->getRecord(300, 'foo', ['exception' => $exception]);
        
        $formatter = m::mock('Monolog\\Formatter\\FormatterInterface');
        
        $handlerMonolog = new WhoopsHandler($this->handlerWhoops);
             
        $this->handlerWhoops
            ->shouldReceive('setInspector')
            ->once()
            ->with(m::type('Whoops\Exception\Inspector'));
              
        $this->handlerWhoops
            ->shouldReceive('setRun')
            ->once()
            ->with(m::type('Whoops\Run'));
               
        $this->handlerWhoops
            ->shouldReceive('setException')
            ->once()
            ->with($exception);
             
        $this->handlerWhoops
            ->shouldReceive('handle')
            ->once();
             
        $handlerMonolog->handle($record);
    }
}
