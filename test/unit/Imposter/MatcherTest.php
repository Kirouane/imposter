<?php

namespace Imposter\Imposter;

use Imposter\PredicateFactory;

class MatcherTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function matchSucceed()
    {
        $mock    = new \Imposter\Model\Mock();
        $service = new \Imposter\Imposter\Matcher($mock);

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
        $mock = new \Imposter\Model\Mock();
        $mock->setRequestUriPath((new PredicateFactory())->equals('/path'));

        $service = new \Imposter\Imposter\Matcher($mock);

        $request = \Mockery::mock(\RingCentral\Psr7\ServerRequest::class);
        $request->shouldReceive('getUri->getPath')->andReturn('/none')->once();
        $exceptions = $service->match($request);
        self::assertInternalType('array', $exceptions);
        self::assertCount(1, $exceptions);
    }
}
