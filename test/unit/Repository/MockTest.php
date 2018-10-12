<?php

namespace Imposter\Repository;

use Imposter\PredicateFactory;
use Monolog\Logger;
use PHPUnit\Framework\Constraint\IsIdentical;

class MockTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function recreate()
    {
        $repository = new \Imposter\Repository\Mock(\Mockery::mock(Logger::class));
        $repository->insert(new \Imposter\Model\Mock());
        self::assertTrue($repository->hasData());
        $repository->recreate();
        self::assertFalse($repository->hasData());
    }

    /**
     * @test
     */
    public function insert()
    {
        $repository = new \Imposter\Repository\Mock(\Mockery::mock(Logger::class));
        $mock       = $repository->insert(new \Imposter\Model\Mock());
        self::assertInstanceOf(\Imposter\Model\Mock::class, $mock);
        self::assertInternalType('string', $mock->getId());
    }

    /**
     * @test
     */
    public function findById()
    {
        $repository = new \Imposter\Repository\Mock(\Mockery::mock(Logger::class));
        $mock       = $repository->insert(new \Imposter\Model\Mock());
        $mock       = $repository->findById($mock->getId());
        self::assertInstanceOf(\Imposter\Model\Mock::class, $mock);
        self::assertInternalType('string', $mock->getId());
    }

    /**
     * @test
     */
    public function update()
    {
        $repository = new \Imposter\Repository\Mock(\Mockery::mock(Logger::class));
        $mock       = $repository->insert(new \Imposter\Model\Mock());
        $mock->setPort(11);
        $repository->update($mock);
        $mock = $repository->findById($mock->getId());
        self::assertInstanceOf(\Imposter\Model\Mock::class, $mock);
        self::assertInternalType('string', $mock->getId());
        self::assertSame(11, $mock->getPort());
    }

    /**
     * @test
     */
    public function drop()
    {
        $repository = new \Imposter\Repository\Mock(\Mockery::mock(Logger::class));
        $repository->insert(new \Imposter\Model\Mock());
        self::assertTrue($repository->hasData());
        $repository->drop();
        self::assertFalse($repository->hasData());
    }

    /**
     * @test
     */
    public function matchRequestSuccess()
    {
        $repository = new \Imposter\Repository\Mock(
            \Mockery::mock(Logger::class)
            ->shouldReceive('info')
            ->getMock()
        );
        $mock       = $repository->insert(new \Imposter\Model\Mock());
        $mock->setRequestUriPath((new PredicateFactory())->equals('/path'));


        $request = \Mockery::mock(\RingCentral\Psr7\ServerRequest::class);
        $request->shouldReceive('getUri->getPath')->andReturn('/path')->once();

        $found = $repository->matchRequest($request);
        self::assertInstanceOf(\Imposter\Model\Mock::class, $found);
        self::assertSame($found->getId(), $mock->getId());
    }

    /**
     * @test
     */
    public function matchRequestFail()
    {
        $repository = new \Imposter\Repository\Mock(\Mockery::mock(Logger::class)->shouldReceive('warning')->getMock());
        $mock       = $repository->insert(new \Imposter\Model\Mock());
        $mock->setRequestUriPath((new PredicateFactory())->equals('/path'));

        $request = \Mockery::mock(\RingCentral\Psr7\ServerRequest::class);
        $request->shouldReceive('getUri->getPath')->andReturn('/none')->once();

        $found = $repository->matchRequest($request);
        self::assertNull($found);
    }

    /**
     * @test
     */
    public function matchRequestMultipleMockSuccess()
    {
        $repository = new \Imposter\Repository\Mock(
            \Mockery::mock(Logger::class)
                ->shouldReceive('info')
                ->getMock()
                ->shouldReceive('warning')
                ->getMock()
        );
        $mock       = $repository->insert(new \Imposter\Model\Mock());
        $mock->setRequestUriPath((new PredicateFactory())->equals('/path1'));

        $mock       = $repository->insert(new \Imposter\Model\Mock());
        $mock->setRequestUriPath((new PredicateFactory())->equals('/path2'));


        $request = \Mockery::mock(\RingCentral\Psr7\ServerRequest::class);
        $request->shouldReceive('getUri->getPath')->andReturn('/path2')->once();

        $found = $repository->matchRequest($request);
        self::assertInstanceOf(\Imposter\Model\Mock::class, $found);
        self::assertSame($found->getId(), $mock->getId());
    }
}
