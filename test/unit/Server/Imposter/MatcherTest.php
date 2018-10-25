<?php

namespace Imposter\Server\Imposter;

use PHPUnit\Framework\Constraint\IsIdentical;

class MatcherTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function matchSucceed()
    {
        $mock    = new \Imposter\Common\Model\Mock(1);
        $service = new Matcher\Matcher($mock);

        $request    = \Mockery::mock(\RingCentral\Psr7\ServerRequest::class);
        $exceptions = $service->match($request);
        self::assertInternalType('array', $exceptions);
        self::assertEmpty($exceptions);
    }

    /**
     * @test
     */
    public function matchFailed()
    {
        $mock = new \Imposter\Common\Model\Mock(1);
        $mock->setRequestUriPath(new IsIdentical('/path'));

        $service = new Matcher\Matcher($mock);

        $request = \Mockery::mock(\RingCentral\Psr7\ServerRequest::class);
        $request->shouldReceive('getUri->getPath')->andReturn('/none')->once();
        $exceptions = $service->match($request);
        self::assertInternalType('array', $exceptions);
        self::assertCount(1, $exceptions);
    }

    public function tearDown()
    {
        \Mockery::close();
    }

}
