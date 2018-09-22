<?php

namespace Imposter;

use PHPUnit\Framework\Constraint\RegularExpression;
use PHPUnit\Framework\TestCase;

class ScenarioTest extends TestCase
{

    /**
     * @test
     */
    public function match()
    {
        Imposter::mock(8081)
            ->withPath('/users/1')
            ->withMethod(new RegularExpression('/GET|PUT|POST/'))
            ->returnBody('{"response" :"okay"}')
            ->once()
            ->send();

        $client   = new \GuzzleHttp\Client();
            $response = $client->post('http://localhost:8081/users/1')->getBody()->getContents();
        self::assertSame($response, '{"response" :"okay"}');
        Imposter::close();
    }

    /**
     * @test
     */
    public function notMatch()
    {
        Imposter::mock(8081)
            ->withPath('/users/1')
            ->withMethod(new RegularExpression('/PUT|POST/'))
            ->returnBody('{"response" :"okay"}')
            ->send();

        $client   = new \GuzzleHttp\Client();

        $e = null;
        try {
            $response = $client->get('http://localhost:8081/users/1')->getBody()->getContents();
            Imposter::close();
        } catch (\Exception $e) {

        }

        self::assertNotNull($e);
    }

    /**
     * @test
     */
    public function matchBugPredictionFails()
    {
        Imposter::mock(8081)
            ->withPath('/users/1')
            ->withMethod(new RegularExpression('/PUT|POST/'))
            ->returnBody('{"response" :"okay"}')
            ->twice()
            ->send();

        $client   = new \GuzzleHttp\Client();

        $e = null;
        $response = $client->post('http://localhost:8081/users/1')->getBody()->getContents();
        try {
            Imposter::close();
        } catch (\Exception $e) {

        }

        self::assertNotNull($e);
    }
}
