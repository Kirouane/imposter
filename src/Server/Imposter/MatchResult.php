<?php
declare(strict_types=1);

namespace Imposter\Server\Imposter;

use Imposter\Common\Model\MockAbstract;

/**
 * Class MatchResult
 * @package Imposter\Server\Imposter
 */
class MatchResult
{
    /**
     * @var MockAbstract
     */
    private $mock;

    /**
     * @var \Exception[]
     */
    private $exceptions;

    /**
     * MatchResult constructor.
     * @param MockAbstract $mock
     * @param \Exception[] $exceptions
     */
    public function __construct(MockAbstract $mock, array $exceptions)
    {
        $this->mock = $mock;
        $this->exceptions = $exceptions;
    }

    /**
     * @return MockAbstract
     */
    public function getMock(): MockAbstract
    {
        return $this->mock;
    }

    /**
     * @return \Exception[]
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }
}