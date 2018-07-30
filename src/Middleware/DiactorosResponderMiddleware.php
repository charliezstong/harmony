<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\EmitterInterface;
use Zend\Diactoros\Response\SapiEmitter;

/**
 * @deprecated since 5.1.0. Use HttpHandlerRunnerMiddleware along with the zendframework/zend-httphandlerrunner package
 */
class DiactorosResponderMiddleware implements MiddlewareInterface
{
    /**
     * @var EmitterInterface
     */
    protected $emitter;

    /**
     * @var bool
     */
    protected $checkOutputStart;

    public function __construct(?EmitterInterface $emitter = null, bool $checkOutputStart = false)
    {
        $this->emitter = $emitter ?? new SapiEmitter();
        $this->checkOutputStart = $checkOutputStart;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        if ($this->checkOutputStart === false || headers_sent() === false) {
            $this->emitter->emit($response);
        }

        return $response;
    }

    public function getEmitter(): EmitterInterface
    {
        return $this->emitter;
    }

    public function setEmitter(EmitterInterface $emitter): void
    {
        $this->emitter = $emitter;
    }

    public function isOutputStartChecked(): bool
    {
        return $this->checkOutputStart;
    }

    public function setCheckOutputStart(bool $checkOutputStart): void
    {
        $this->checkOutputStart = $checkOutputStart;
    }
}
