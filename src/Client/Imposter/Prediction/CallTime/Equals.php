<?php
declare(strict_types=1);

namespace Imposter\Client\Imposter\Prediction\CallTime;

use PHPUnit\Framework\TestCase;

/**
 * Class Equals
 * @package Imposter\Imposter\Prediction\CallTime
 */
class Equals extends AbstractCallTime
{
    /**
     * @param int $times
     */
    public function check(int $times)
    {
        if ($this->times !== $times) {
            TestCase::fail($this->getMessage($times));
        }
    }

    /**
     * @param $times
     * @return string
     */
    private function getMessage($times): string
    {
        return sprintf(
           "Expected exactly %d calls that match:\n" .
            "%s \n" .
            'but %d were made.',
            $this->mock->toString(),
            $this->times,
            $times
       );
    }
}
