<?php
namespace WoohooLabsTest\Harmony\Utils\Diactoros;

use Psr\Http\Message\ResponseInterface;
use WoohooLabsTest\Harmony\Utils\Exception\TestException;
use Zend\Diactoros\Response\EmitterInterface;

class DummyEmitter implements EmitterInterface
{
    /**
     * Emit a response.
     *
     * Emits a response, including status line, headers, and the message body,
     * according to the environment.
     *
     * Implementations of this method may be written in such a way as to have
     * side effects, such as usage of header() or pushing output to the
     * output buffer.
     *
     * Implementations MAY raise exceptions if they are unable to emit the
     * response; e.g., if headers have already been sent.
     *
     * @param ResponseInterface $response
     * @return bool
     */
    public function emit(ResponseInterface $response)
    {
        echo "true";
    }
}
