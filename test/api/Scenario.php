<?php
namespace Test\Api;

use Guzzle\Http\Client;
use Imposter\Imposter;
use PHPUnit\Framework\TestCase;

class Scenario extends TestCase
{
    /**
     * @test
     */
    public function scenario()
    {
        $mock = Imposter::mock(8081)
            ->withPath('test-api')
            ->withBody('{}')
            ->withMethod('GET')
            ->returnBody('{"response" :"okay"}')
            ->once()
            ->send();


        $client = new Client();
        $response = $client->get('http://localhost:8081/test-api')->send()->getBody(true);
        self::assertSame($response, '{"response" :"okay"}');
        $mock->resolve();
    }
}