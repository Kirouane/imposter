<?php

namespace Imposter\Server\Repository;

use Monolog\Logger;
use PHPUnit\Framework\Constraint\IsIdentical;

class MockTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function recreate()
    {
        $repository = new \Imposter\Server\Repository\Mock(\Mockery::mock(Logger::class));
        $repository->insert(new \Imposter\Common\Model\Mock(1));
        self::assertTrue($repository->hasData());
        $repository->recreate();
        self::assertFalse($repository->hasData());
    }

    /**
     * @test
     */
    public function insert()
    {
        $repository = new \Imposter\Server\Repository\Mock(\Mockery::mock(Logger::class));
        $mock       = $repository->insert(new \Imposter\Common\Model\Mock(1));
        self::assertInstanceOf(\Imposter\Common\Model\Mock::class, $mock);
        self::assertInternalType('string', $mock->getId());
    }

    /**
     * @test
     */
    public function findById()
    {
        $repository = new \Imposter\Server\Repository\Mock(\Mockery::mock(Logger::class));
        $mock       = $repository->insert(new \Imposter\Common\Model\Mock(1));
        $mock       = $repository->findById(1, $mock->getId());
        self::assertInstanceOf(\Imposter\Common\Model\Mock::class, $mock);
        self::assertInternalType('string', $mock->getId());
    }

    /**
     * @test
     */
    public function update()
    {
        $repository = new \Imposter\Server\Repository\Mock(\Mockery::mock(Logger::class));
        $mock       = $repository->insert(new \Imposter\Common\Model\Mock(1));
        $mock->setPort(11);
        $repository->update($mock);
        $mock = $repository->findById(1, $mock->getId());
        self::assertInstanceOf(\Imposter\Common\Model\Mock::class, $mock);
        self::assertInternalType('string', $mock->getId());
        self::assertSame(11, $mock->getPort());
    }

    /**
     * @test
     */
    public function drop()
    {
        $repository = new \Imposter\Server\Repository\Mock(\Mockery::mock(Logger::class));
        $repository->insert(new \Imposter\Common\Model\Mock(1));
        self::assertTrue($repository->hasData());
        $repository->drop();
        self::assertFalse($repository->hasData());
    }

    /**
     * @test
     */
    public function matchRequestSuccess()
    {
        $repository = new \Imposter\Server\Repository\Mock(
            \Mockery::mock(Logger::class)
            ->shouldReceive('info')
            ->getMock()
        );
        $mock       = $repository->insert(new \Imposter\Common\Model\Mock(1));
        $mock->setRequestUriPath(new IsIdentical('/path'));


        $request = \Mockery::mock(\RingCentral\Psr7\ServerRequest::class);
        $request->shouldReceive('getUri->getPath')->andReturn('/path')->once();
        $request->shouldReceive('getUri->getPort')->andReturn(1);

        $found = $repository->matchRequest($request);
        self::assertInstanceOf(\Imposter\Common\Model\Mock::class, $found);
        self::assertSame($found->getId(), $mock->getId());
    }

    /**
     * @test
     */
    public function matchRequestFail()
    {
        $repository = new \Imposter\Server\Repository\Mock(\Mockery::mock(Logger::class)->shouldReceive('warning')->getMock());
        $mock       = $repository->insert(new \Imposter\Common\Model\Mock(1));
        $mock->setRequestUriPath(new IsIdentical('/path'));

        $request = \Mockery::mock(\RingCentral\Psr7\ServerRequest::class);
        $request->shouldReceive('getUri->getPath')->andReturn('/none')->once();
        $request->shouldReceive('getUri->getPort')->andReturn(1)->once();

        $found = $repository->matchRequest($request);
        self::assertNull($found);
    }

    /**
     * @test
     */
    public function matchRequestMultipleMockSuccess()
    {
        $repository = new \Imposter\Server\Repository\Mock(
            \Mockery::mock(Logger::class)
                ->shouldReceive('info')
                ->getMock()
                ->shouldReceive('warning')
                ->getMock()
        );
        $mock       = $repository->insert(new \Imposter\Common\Model\Mock(1));
        $mock->setRequestUriPath(new IsIdentical('/path1'));

        $mock       = $repository->insert(new \Imposter\Common\Model\Mock(1));
        $mock->setRequestUriPath(new IsIdentical('/path2'));


        $request = \Mockery::mock(\RingCentral\Psr7\ServerRequest::class);
        $request->shouldReceive('getUri->getPath')->andReturn('/path2');
        $request->shouldReceive('getUri->getPort')->andReturn(1);

        $found = $repository->matchRequest($request);
        self::assertInstanceOf(\Imposter\Common\Model\Mock::class, $found);
        self::assertSame($found->getId(), $mock->getId());
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}
