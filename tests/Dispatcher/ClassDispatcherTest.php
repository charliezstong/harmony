<?php
namespace WoohooLabsTest\Harmony\Dispatcher;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Harmony\Container\BasicContainer;
use WoohooLabs\Harmony\Dispatcher\ClassDispatcher;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyResponse;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyServerRequest;

class ClassDispatcherTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \WoohooLabs\Harmony\Dispatcher\ClassDispatcher::dispatch()
     * @covers \WoohooLabs\Harmony\Dispatcher\ClassDispatcher::getContainer()
     * @covers \WoohooLabs\Harmony\Dispatcher\ClassDispatcher::getClassName()
     * @covers \WoohooLabs\Harmony\Dispatcher\ClassDispatcher::getMethodName()
     */
    public function testClassDispatcher()
    {
        $dispatcher = new ClassDispatcher(new BasicContainer(), DummyController::class, "dummyAction");
        $response = $dispatcher->dispatch(new DummyServerRequest(), new DummyResponse());

        $this->assertInstanceOf(DummyResponse::class, $response);
        $this->assertInstanceOf(BasicContainer::class, $dispatcher->getContainer());
        $this->assertEquals(DummyController::class, $dispatcher->getClassName());
        $this->assertEquals("dummyAction", $dispatcher->getMethodName());
    }
}
