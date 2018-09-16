<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/16/18
 * Time: 5:16 PM
 */

class AtLeastTest extends \PHPUnit\Framework\TestCase
{


    public function checkProvider()
    {
        return [
            [0, 0, false],
            [1, 1, false],
            [1, 0, true],
            [0, 1, false],
        ];
    }

    /**
     * @test
     * @dataProvider checkProvider
     */
    public function check($times, $expectedTimes, $expectedException)
    {
        $callTime = new \Imposter\Imposter\Prediction\CallTime\AtLeast($expectedTimes, new \Imposter\Model\Mock());
        $hasException = false;

        try {
            $callTime->check($times);
        } catch (\Exception $e) {
            $hasException = true;
        }

        self::assertSame($expectedException, $hasException);
    }
}