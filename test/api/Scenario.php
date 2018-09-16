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

        Imposter::mock(8081)
            ->withPath('users/1')
            ->withBody('{}')
            ->withMethod('GET')
            ->returnBody('{"response" :"okay"}')
            ->once()
            ->send();

        $client = new Client();
        $response = $client->get('http://localhost:8081/users/1')->send()->getBody(true);
        self::assertSame($response, '{"response" :"okay"}');

    }

    public function tearDown()
    {
        Imposter::close();
    }
}