<?php
declare(strict_types=1);

namespace Imposter\Client\Imposter\Prediction\CallTime;

use PHPUnit\Framework\TestCase;

/**
 * Class AtMost
 * @package Imposter\Imposter\Prediction\CallTime
 */
class AtMost extends AbstractCallTime
{
    /**
     * @param int $times
     */
    public function check(int $times)
    {
        if ($this->times < $times) {
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
            "Expected at most %d calls that match:\n" .
            "%s \n" .
            'but %d were made.',
            $this->mock->toString(),
            $this->times,
            $times
        );
    }
}
