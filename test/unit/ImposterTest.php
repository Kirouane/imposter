<?php

namespace Imposter;

use Imposter\Imposter\Prediction\CallTime\AtLeast;
use Imposter\Imposter\Prediction\CallTime\AtMost;
use Imposter\Imposter\Prediction\CallTime\Equals;
use Imposter\Model\Mock;
use Imposter\Repository\HttpMock;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\TestCase;

class ImposterTest extends TestCase
{
    /**
     * @test
     */
    public function mock()
    {
        $respository = \Mockery::mock(HttpMock::class);
        $respository->shouldReceive('drop')->once();
        $respository->shouldReceive('insert')->once()->with(\Mockery::on(function (Mock $mock) {
            self::assertInstanceOf(Mock::class, $mock);
            self::assertInstanceOf(Constraint::class, $mock->getRequestBody());
            self::assertInstanceOf(Constraint::class, $mock->getRequestUriPath());
            self::assertInstanceOf(Constraint::class, $mock->getRequestMethod());
            self::assertSame('body', $mock->getResponseBody());
            return true;
        }))->andReturn(new Mock());

        $respository->shouldReceive('find')->once()->andReturn(new Mock());
        Imposter::setRepository($respository);
        $imposter = Imposter::mock(1);
        self::assertInstanceOf(Imposter::class, $imposter);

        $imposter
            ->withBody('body')
            ->withMethod('method')
            ->withPath('path')
            ->returnBody('body');

        $imposter->send();

        $imposter->once();
        self::assertInstanceOf(Equals::class, $imposter->getCallTimePrediction());

        $imposter->twice();
        self::assertInstanceOf(Equals::class, $imposter->getCallTimePrediction());

        $imposter->never();
        self::assertInstanceOf(Equals::class, $imposter->getCallTimePrediction());

        $imposter->times(0);
        self::assertInstanceOf(Equals::class, $imposter->getCallTimePrediction());

        $imposter->atLeast(0);
        self::assertInstanceOf(AtLeast::class, $imposter->getCallTimePrediction());

        $imposter->atMost(0);
        self::assertInstanceOf(AtMost::class, $imposter->getCallTimePrediction());

        Imposter::close();
    }
}
