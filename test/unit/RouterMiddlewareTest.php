<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/20/18
 * Time: 5:39 PM
 */

namespace Imposter;


use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;

class RouterMiddlewareTest extends TestCase
{

    /**
     * @test
     */
    public function invokeTest()
    {
        $di = \Mockery::mock(Di::class);
        $di->shouldReceive('get')->with('output')->andReturn(
            \Mockery::mock(OutputInterface::class)
                ->shouldReceive('writeln')
                ->getMock()
        );

        $di->shouldReceive('get')->with('Imposter\Api\Controller\Mock\Test\Get')->andReturn(function() {
            return new \React\Http\Response(200);
        });

        $request = \Mockery::mock(\Psr\Http\Message\ServerRequestInterface::class);
        $request->shouldReceive('getUri->getPath')->andReturn('mock/test');
        $request->shouldReceive('getMethod')->andReturn('GET');


        $middleware = new \Imposter\RouterMiddleware($di);
        $response = $middleware->__invoke($request);
        self::assertInstanceOf( \React\Http\Response::class, $response);
        self::assertSame(200, $response->getStatusCode());
    }
}