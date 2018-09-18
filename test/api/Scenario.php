<?php

namespace Imposter;

use PHPUnit\Framework\TestCase;

class Scenario extends TestCase
{
    /**
     * @test
     */
    public function scenario()
    {
        Imposter::mock(8081)
            ->withPath('/users/1')
            ->withMethod('GET')
            ->returnBody('{"response" :"okay"}')
            ->once()
            ->send();

        $client   = new \GuzzleHttp\Client();
        $response = $client->get('http://localhost:8081/users/1')->send()->getBody(true);
        self::assertSame($response, '{"response" :"okay"}');
    }

    public function tearDown()
    {
        Imposter::close();
    }
}
