<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/16/18
 * Time: 5:02 PM
 */

namespace Imposter\Imposter\Prediction\CallTime;

use Imposter\Model\Mock;

abstract class AbstractCallTime
{
    /**
     * @var int
     */
    protected $times;

    /**
     * @var Mock
     */
    protected $mock;

    /**
     * Equals constructor.
     * @param int $times
     */
    public function __construct(int $times, Mock $mock)
    {
        $this->times = $times;
        $this->mock  = $mock;
    }

    abstract public function check($times);
}
