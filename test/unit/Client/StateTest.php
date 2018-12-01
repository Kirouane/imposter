<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 11/24/18
 * Time: 9:13 PM
 */

namespace Imposter\Client;


use PHPUnit\Framework\TestCase;

class StateTest extends TestCase
{
    /**
     * @test
     */
    public function capture()
    {
        $client = \Mockery::mock(\Imposter\Client\Http::class);
        $client->shouldReceive('isStarted')->andReturn(true);
        $client->shouldReceive('drop');
        $state = new State($client);

        $state->capture();
        self::assertTrue($state->isInitialized());

        $state->release();
        self::assertFalse($state->isInitialized());

    }

    /**
     * @test
     */
    public function captureAlreadyStarted()
    {
        $client = \Mockery::mock(\Imposter\Client\Http::class);
        $client->shouldReceive('isStarted')->andReturn(false);
        $client->shouldReceive('restart');
        $client->shouldReceive('drop');
        $state = new State($client);

        $state->capture();
        self::assertTrue($state->isInitialized());
    }

    /**
     * @test
     */
    public function store()
    {
        $client = \Mockery::mock(\Imposter\Client\Http::class);
        $client->shouldReceive('isStarted')->andReturn(true);
        $client->shouldReceive('stop');
        $state = new State($client);

        $state->stop();
        self::assertFalse($state->isInitialized());
    }
}