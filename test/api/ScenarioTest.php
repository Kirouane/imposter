<?php

namespace Imposter;

use PHPUnit\Framework\Constraint\RegularExpression;
use PHPUnit\Framework\TestCase;

class ScenarioTest extends TestCase
{
    /**
     * @test
     */
    public function scenario()
    {
        Imposter::mock(8081)
            ->withPath('/users/1')
            ->withMethod(new RegularExpression('/PUT|POST/'))
            ->returnBody('{"response" :"okay"}')
            ->once()
            ->send();

        $client   = new \GuzzleHttp\Client();
        $response = $client->post('http://localhost:8081/users/1')->getBody()->getContents();
        self::assertSame($response, '{"response" :"okay"}');
    }

    public function tearDown()
    {
        Imposter::close();
    }
}
