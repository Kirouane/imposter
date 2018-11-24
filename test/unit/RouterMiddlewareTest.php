<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/20/18
 * Time: 5:39 PM
 */

namespace Imposter;


use Imposter\Common\Container;
use Imposter\Common\Di;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;

class RouterMiddlewareTest extends TestCase
{

    /**
     * @test
     */
    public function invokeTest()
    {
        $di = \Mockery::mock(Container::class);
        $di->shouldReceive('get')->with('output')->andReturn(
            \Mockery::mock(OutputInterface::class)
                ->shouldReceive('writeln')
                ->getMock()
        );

        $di->shouldReceive('get')->with('Imposter\Server\Api\Controller\Mock\Test\Get')->andReturn(function() {
            return new \React\Http\Response(200);
        });
        $di->shouldReceive('get')->with('logger');
        $request = \Mockery::mock(\Psr\Http\Message\ServerRequestInterface::class);
        $request->shouldReceive('getUri->getPath')->andReturn('mock/test');
        $request->shouldReceive('getMethod')->andReturn('GET');


        $middleware = new Server\RouterMiddleware($di);
        $response = $middleware->__invoke($request);
        self::assertInstanceOf( \React\Http\Response::class, $response);
        self::assertSame(200, $response->getStatusCode());
    }
}