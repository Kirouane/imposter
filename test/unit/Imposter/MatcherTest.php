<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/17/18
 * Time: 12:36 PM
 */

class MatcherTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function matchSucceed()
    {
        $mock = new \Imposter\Model\Mock();
        $service = new \Imposter\Imposter\Matcher($mock);

        $request = Mockery::mock(\RingCentral\Psr7\ServerRequest::class);
        $exceptions = $service->match($request);
        self::assertInternalType('array', $exceptions);
        self::assertEmpty($exceptions);
    }


    /**
     * @test
     */
    public function matchFailed()
    {
        $mock = new \Imposter\Model\Mock();
        $mock->setRequestUriPath(new \PHPUnit\Framework\Constraint\IsIdentical('/path'));
        $service = new \Imposter\Imposter\Matcher($mock);

        $request = Mockery::mock(\RingCentral\Psr7\ServerRequest::class);
        $request->shouldReceive('getUri->getPath')->andReturn('/none')->once();
        $exceptions = $service->match($request);
        self::assertInternalType('array', $exceptions);
        self::assertCount(1, $exceptions);
    }
}