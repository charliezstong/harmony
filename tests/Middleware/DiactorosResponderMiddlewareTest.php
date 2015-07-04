<?php
namespace WoohooLabsTest\Harmony\Middleware;

use FastRoute\Dispatcher;
use PHPUnit_Framework_TestCase;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\DiactorosResponderMiddleware;
use WoohooLabsTest\Harmony\Utils\Container\DummyContainer;
use WoohooLabsTest\Harmony\Utils\Diactoros\DummyEmitter;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyResponse;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyServerRequest;

class DiactorosResponderMiddlewareTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \WoohooLabs\Harmony\Middleware\DiactorosResponderMiddleware::__construct()
     * @covers \WoohooLabs\Harmony\Middleware\DiactorosResponderMiddleware::getEmitter()
     */
    public function testConstruct()
    {
        $middleware = new DiactorosResponderMiddleware(new DummyEmitter());

        $this->assertInstanceOf(DummyEmitter::class, $middleware->getEmitter());
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\DiactorosResponderMiddleware::getEmitter()
     * @covers \WoohooLabs\Harmony\Middleware\DiactorosResponderMiddleware::setEmitter()
     */
    public function testSetEmitter()
    {
        $middleware = new DiactorosResponderMiddleware(null);
        $middleware->setEmitter(new DummyEmitter());

        $this->assertInstanceOf(DummyEmitter::class, $middleware->getEmitter());
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\DiactorosResponderMiddleware::__invoke()
     */
    public function testInvoke()
    {
        $harmony = $this->createHarmony();
        $middleware = new DiactorosResponderMiddleware(new DummyEmitter());

        $this->expectOutputString("true");
        $middleware($harmony->getRequest(), $harmony->getResponse(), $harmony);
    }

    /**
     * @return \WoohooLabs\Harmony\Harmony
     */
    private function createHarmony()
    {
        return new Harmony(new DummyServerRequest(), new DummyResponse());
    }
}
