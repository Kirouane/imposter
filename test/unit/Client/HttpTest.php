<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 11/18/18
 * Time: 12:16 PM
 */

namespace Imposter\Client;


use Imposter\Common\Model\Mock;
use Imposter\Common\Model\MockAbstract;
use PHPUnit\Framework\TestCase;

class HttpTest extends TestCase
{

    public function mockWithExceptionsProvider()
    {
        return [
            [400, '', true],
            [200, '', true],
            [201, '', true],
            [201, 'something', true],
            [201, serialize(new Mock(1)), false]
        ];
    }

    /**
     * @test
     * @dataProvider mockWithExceptionsProvider
     */
    public function insertMockWithExceptions($code, $content, $withException)
    {
        if ($withException) {
            self::expectException(\Exception::class);
        }

        $mock = \Mockery::mock(MockAbstract::class);
        $response = \Mockery::mock(\Psr\Http\Message\ResponseInterface::class);
        $response->shouldReceive('getBody->getContents')->andReturn($content);
        $response->shouldReceive('getStatusCode')->andReturn($code);

        $client = \Mockery::mock(\GuzzleHttp\Client::class);
        $client->shouldReceive('post')->andReturn($response);
        $http = new Http($client, \Mockery::mock(Console::class));

        $returnedMock =$http->insert($mock);
        self::assertInstanceOf(MockAbstract::class, $returnedMock);
    }

    /**
     * @test
     * @dataProvider mockWithExceptionsProvider
     */
    public function findMockWithExceptions($code, $content, $withException)
    {
        if ($withException) {
            self::expectException(\Exception::class);
        }

        $mock = \Mockery::mock(MockAbstract::class);
        $mock->shouldReceive('getId')->andReturn('id');
        $mock->shouldReceive('getPort')->andReturn(1);

        $response = \Mockery::mock(\Psr\Http\Message\ResponseInterface::class);
        $response->shouldReceive('getBody->getContents')->andReturn($content);
        $response->shouldReceive('getStatusCode')->andReturn($code);

        $client = \Mockery::mock(\GuzzleHttp\Client::class);
        $client->shouldReceive('get')->andReturn($response);
        $http = new Http($client, \Mockery::mock(Console::class));

        $returnedMock =$http->find($mock);
        self::assertInstanceOf(MockAbstract::class, $returnedMock);
    }

    public function findAllmockWithExceptionsProvider()
    {
        return [
            [400, '', true],
            [200, '', true],
            [201, '', true],
            [201, 'something', true],
            [201, serialize(new Mock(1)), true],
            [201, serialize([new Mock(1)]), false]
        ];
    }


    /**
     * @test
     * @dataProvider findAllmockWithExceptionsProvider
     */
    public function findAllMockWithExceptions($code, $content, $withException)
    {
        if ($withException) {
            self::expectException(\Exception::class);
        }

        $mock = \Mockery::mock(MockAbstract::class);
        $mock->shouldReceive('getId')->andReturn('id');
        $mock->shouldReceive('getPort')->andReturn(1);

        $response = \Mockery::mock(\Psr\Http\Message\ResponseInterface::class);
        $response->shouldReceive('getBody->getContents')->andReturn($content);
        $response->shouldReceive('getStatusCode')->andReturn($code);

        $client = \Mockery::mock(\GuzzleHttp\Client::class);
        $client->shouldReceive('get')->andReturn($response);
        $http = new Http($client, \Mockery::mock(Console::class));

        $returnedMock = $http->findAll($mock);
        self::assertContainsOnlyInstancesOf(MockAbstract::class, $returnedMock);
    }

    /**
     * @test
     */
    public function drop()
    {
        $client = \Mockery::mock(\GuzzleHttp\Client::class);
        $client->shouldReceive('delete')->once();
        $http = new Http($client, \Mockery::mock(Console::class));
        self::assertNull($http->drop());
    }

    /**
     * @test
     */
    public function startImposterThanStarted()
    {
        $response = \Mockery::mock(\Psr\Http\Message\ResponseInterface::class);
        $response->shouldReceive('getBody->getContents')->andReturn(serialize([]));
        $response->shouldReceive('getStatusCode')->andReturn(400, 201);

        $client = \Mockery::mock(\GuzzleHttp\Client::class);
        $client->shouldReceive('get')->andReturn($response);

        $console = \Mockery::mock(Console::class);
        $console->shouldReceive('startImposter');

        $mock = \Mockery::mock(MockAbstract::class);
        $mock->shouldReceive('getId')->andReturn('id');
        $mock->shouldReceive('getPort')->andReturn(1);


        $http = new Http($client, $console);
        $http->start();
    }

    /**
     * @test
     */
    public function startImposterCannotBeStarted()
    {
        $this->expectException(\RuntimeException::class);
        $response = \Mockery::mock(\Psr\Http\Message\ResponseInterface::class);
        $response->shouldReceive('getBody->getContents')->andReturn(serialize([]));
        $response->shouldReceive('getStatusCode')->andReturn(400);

        $client = \Mockery::mock(\GuzzleHttp\Client::class);
        $client->shouldReceive('get')->andReturn($response);

        $console = \Mockery::mock(Console::class);
        $console->shouldReceive('startImposter');

        $mock = \Mockery::mock(MockAbstract::class);
        $mock->shouldReceive('getId')->andReturn('id');
        $mock->shouldReceive('getPort')->andReturn(1);

        $http = new Http($client, $console);
        $http->setTimeout(0.1);
        $http->start();
    }


    /**
     * @test
     */
    public function stopSuccess()
    {
        $client = \Mockery::mock(\GuzzleHttp\Client::class);
        $client->shouldReceive('delete')->once();
        $http = new Http($client, \Mockery::mock(Console::class));
        self::assertTrue($http->stop());
    }

    /**
     * @test
     */
    public function stopFail()
    {
        $client = \Mockery::mock(\GuzzleHttp\Client::class);
        $client->shouldReceive('delete')->once()->andThrow(\Exception::class);
        $http = new Http($client, \Mockery::mock(Console::class));
        self::assertFalse($http->stop());
    }

    /**
     * @test
     */
    public function restart()
    {
        $response = \Mockery::mock(\Psr\Http\Message\ResponseInterface::class);
        $response->shouldReceive('getBody->getContents')->andReturn(serialize([]));
        $response->shouldReceive('getStatusCode')->andReturn(400, 201);

        $client = \Mockery::mock(\GuzzleHttp\Client::class);
        $client->shouldReceive('get')->andReturn($response);

        $console = \Mockery::mock(Console::class);
        $console->shouldReceive('startImposter');

        $mock = \Mockery::mock(MockAbstract::class);
        $mock->shouldReceive('getId')->andReturn('id');
        $mock->shouldReceive('getPort')->andReturn(1);

        $http = new Http($client, $console);
        self::assertTrue($http->restart());
    }

    public function tearDown()
    {
        $this->addToAssertionCount(\Mockery::getContainer()->mockery_getExpectationCount());
        \Mockery::close();
    }
}