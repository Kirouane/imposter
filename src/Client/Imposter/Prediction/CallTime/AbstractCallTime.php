<?php
declare(strict_types=1);


namespace Imposter\Client\Imposter\Prediction\CallTime;

use Imposter\Common\Model\MockAbstract;

/**
 * Class AbstractCallTime
 * @package Imposter\Imposter\Prediction\CallTime
 */
abstract class AbstractCallTime
{
    /**
     * @var int
     */
    protected $times;

    /**
     * @var MockAbstract
     */
    protected $mock;

    /**
     * Equals constructor.
     * @param int $times
     * @param \Imposter\Common\Model\MockAbstract $mock
     */
    public function __construct(int $times, MockAbstract $mock)
    {
        $this->times = $times;
        $this->mock  = $mock;
    }

    /**
     * @param int $times
     * @return void
     */
    abstract public function check(int $times);
}
