<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 11/19/18
 * Time: 8:56 AM
 */

namespace Imposter\Client\Imposter;


use Imposter\Common\Model\Mock;
use PHPUnit\Framework\Constraint\ArraySubset;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\TestCase;

class MockBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function mockShouldHaveConstraints()
    {
        $repository = \Mockery::mock(\Imposter\Client\Http::class);
        $builder = new MockBuilder(1, $repository);
        $builder
            ->withMethod('POST')
            ->withBody('{}')
            ->withPath('/')
            ->withHeaders(['key' => 'value'])
            ->returnBody('[]')
            ->returnHeaders(['response_key' => 'response_value']);

        $mock = $builder->getMock();

        self::assertInstanceOf(Mock::class, $mock);
        self::assertInstanceOf(IsIdentical::class, $mock->getRequestMethod());
        self::assertInstanceOf(IsIdentical::class, $mock->getRequestBody());
        self::assertInstanceOf(IsIdentical::class, $mock->getRequestUriPath());
        self::assertInstanceOf(ArraySubset::class, $mock->getRequestHeaders());

        self::assertSame('[]', $mock->getResponseBody());
        self::assertSame(['response_key' => 'response_value'], $mock->getResponseHeaders());
    }

    /**
     * @test
     */
    public function send()
    {
        $repository = \Mockery::mock(\Imposter\Client\Http::class);
        $repository->shouldReceive('insert')->andReturn(new Mock(1));
        $builder = new MockBuilder(1, $repository);
        $builder->send();
        $mock = $builder->getMock();
        self::assertInstanceOf(Mock::class, $mock);
    }

    public function resolveTimesMonadeProvider()
    {
        return [
            ['never', 0, false],
            ['never', 1, true],
            ['never', 2, true],

            ['once', 0, true],
            ['once', 1, false],
            ['once', 2, true],

            ['twice', 0, true],
            ['twice', 1, true],
            ['twice', 2, false],


        ];
    }

    /**
     * @test
     * @dataProvider resolveTimesMonadeProvider
     */
    public function resolveTimesMonade($method, $hits, $expectedException)
    {

        $mock = new Mock(1);
        $mock->setHits($hits);
        $repository = \Mockery::mock(\Imposter\Client\Http::class);
        $repository->shouldReceive('find')->andReturn($mock);
        $builder = new MockBuilder(1, $repository);
        $builder->$method();

        $exception = null;

        try {
            $builder->resolve();
        } catch (\Exception $exception) {

        }

        if ($expectedException) {
            self::assertInstanceOf(\PHPUnit\Framework\AssertionFailedError::class, $exception);
        } else {
            self::assertNull($exception);
        }

    }

    public function resolveTimesTriadeProvider()
    {
        return [
            ['times', 0, 0, false],
            ['times', 0, 1, true],
            ['times', 0, 2, true],

            ['times', 10, 9, true],
            ['times', 10, 10, false],
            ['times', 10, 11, true],

            ['atLeast', 0, 0, false],
            ['atLeast', 0, 1, false],
            ['atLeast', 0, 2, false],

            ['atLeast', 10, 9, true],
            ['atLeast', 10, 10, false],
            ['atLeast', 10, 11, false],

            ['atMost', 0, 0, false],
            ['atMost', 0, 1, true],
            ['atMost', 0, 2, true],

            ['atMost', 10, 9, false],
            ['atMost', 10, 10, false],
            ['atMost', 10, 11, true],
        ];
    }

    /**
     * @test
     * @dataProvider resolveTimesTriadeProvider
     */
    public function resolveTimesTriade($method, $times, $hits, $expectedException)
    {

        $mock = new Mock(1);
        $mock->setHits($hits);
        $repository = \Mockery::mock(\Imposter\Client\Http::class);
        $repository->shouldReceive('find')->andReturn($mock);
        $builder = new MockBuilder(1, $repository);
        $builder->$method($times);

        $exception = null;

        try {
            $builder->resolve();
        } catch (\Exception $exception) {

        }

        if ($expectedException) {
            self::assertInstanceOf(\PHPUnit\Framework\AssertionFailedError::class, $exception);
        } else {
            self::assertNull($exception);
        }

    }

    public function tearDown()
    {
        \Mockery::close();
    }
}