<?php

namespace Imposter\Imposter\Prediction\CallTime;

class AtMostTest extends \PHPUnit\Framework\TestCase
{
    public function checkProvider()
    {
        return [
            [0, 0, false],
            [1, 1, false],
            [1, 0, false],
            [0, 1, true],
        ];
    }

    /**
     * @test
     * @dataProvider checkProvider
     * @param mixed $times
     * @param mixed $expectedTimes
     * @param mixed $expectedException
     */
    public function check($times, $expectedTimes, $expectedException)
    {
        $callTime     = new \Imposter\Client\Imposter\Prediction\CallTime\AtMost($expectedTimes, new \Imposter\Common\Model\Mock());
        $hasException = false;

        try {
            $callTime->check($times);
        } catch (\Exception $e) {
            $hasException = true;
        }

        self::assertSame($expectedException, $hasException);
    }
}
