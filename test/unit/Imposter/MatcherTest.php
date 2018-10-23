<?php

namespace Imposter\Imposter;

use Imposter\Common\PredicateFactory;
use PHPUnit\Framework\Constraint\IsIdentical;

class MatcherTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function matchSucceed()
    {
        $mock    = new \Imposter\Common\Model\Mock();
        $service = new \Imposter\Server\Imposter\Matcher($mock);

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
        $mock = new \Imposter\Common\Model\Mock();
        $mock->setRequestUriPath(new IsIdentical('/path'));

        $service = new \Imposter\Server\Imposter\Matcher($mock);

        $request = \Mockery::mock(\RingCentral\Psr7\ServerRequest::class);
        $request->shouldReceive('getUri->getPath')->andReturn('/none')->once();
        $exceptions = $service->match($request);
        self::assertInternalType('array', $exceptions);
        self::assertCount(1, $exceptions);
    }
}
