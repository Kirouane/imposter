<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/23/18
 * Time: 5:34 PM
 */

namespace Imposter\Imposter;


use Imposter\Model\Mock;

class MatchResult
{
    /**
     * @var Mock
     */
    private $mock;

    /**
     * @var \Exception[]
     */
    private $exceptions;

    /**
     * MatchResult constructor.
     * @param Mock $mock
     * @param \Exception[] $exceptions
     */
    public function __construct(Mock $mock, array $exceptions)
    {
        $this->mock = $mock;
        $this->exceptions = $exceptions;
    }

    /**
     * @return Mock
     */
    public function getMock(): Mock
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