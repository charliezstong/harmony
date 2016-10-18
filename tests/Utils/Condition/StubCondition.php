<?php
declare(strict_types=1);

namespace WoohooLabsTest\Harmony\Utils\Condition;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Condition\ConditionInterface;

class StubCondition implements ConditionInterface
{
    /**
     * @var bool
     */
    protected $result;

    public function __construct(bool $result)
    {
        $this->result = $result;
    }

    public function evaluate(ServerRequestInterface $request, ResponseInterface $response): bool
    {
        return $this->result;
    }
}
