<?php

namespace Imposter;

use PHPUnit\Framework\Constraint\RegularExpression;
use PHPUnit\Framework\TestCase;

/**
 * Class ScenarioTest
 * @package Imposter
 */
class ReadMeTest extends TestCase
{
    /**
     * @test
     *
     */
    public function match()
    {
        Imposter::mock(8081)
            ->withPath('/users/1')
            ->withMethod('POST')
            ->returnBody('{"response" :"okay"}')
            ->once()
            ->send();

        $client   = new \GuzzleHttp\Client();
        $response = $client->post('http://localhost:8081/users/1')->getBody()->getContents();
        self::assertSame($response, '{"response" :"okay"}');
    }

    /**
     * @test
     */
    public function matchRegexp()
    {
        Imposter::mock(8081)
            ->withPath('/users/1')
            ->withMethod(new RegularExpression('/POST|PUT/'))
            ->returnBody('{"response" :"okay"}')
            ->twice()
            ->send();

        $client   = new \GuzzleHttp\Client();
        $response = $client->post('http://localhost:8081/users/1')->getBody()->getContents();
        self::assertSame($response, '{"response" :"okay"}');

        $response = $client->put('http://localhost:8081/users/1')->getBody()->getContents();
        self::assertSame($response, '{"response" :"okay"}');
    }

    public function tearDown()
    {
        Imposter::close();
    }
}
