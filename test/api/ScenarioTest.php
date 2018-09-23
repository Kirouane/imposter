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
    public function matchPredictionFails()
    {
        Imposter::mock(8081)
            ->withPath('/users/1')
            ->withMethod(new RegularExpression('/PUT|POST/'))
            ->returnBody('{"response" :"okay"}')
            ->twice()
            ->send();

        $client   = new \GuzzleHttp\Client();

        $e = null;
        $client->post('http://localhost:8081/users/1')->getBody()->getContents();
        try {
            Imposter::close();
        } catch (\Exception $e) {

        }

        Imposter::reset();

        self::assertNotNull($e);
    }

    /**
     * @test
     */
    public function matchMultipleMock()
    {
        Imposter::mock(8081)
            ->withPath('/users/1')
            ->withMethod(new RegularExpression('/GET|PUT|POST/'))
            ->returnBody('{"response" :"1"}')
            ->once()
            ->send();

        Imposter::mock(8081)
            ->withPath('/users/2')
            ->withMethod(new RegularExpression('/GET|PUT|POST/'))
            ->returnBody('{"response" :"2"}')
            ->once()
            ->send();


        $client   = new \GuzzleHttp\Client();
        $response = $client->post('http://localhost:8081/users/1')->getBody()->getContents();
        self::assertSame($response, '{"response" :"1"}');
        $response = $client->post('http://localhost:8081/users/2')->getBody()->getContents();
        self::assertSame($response, '{"response" :"2"}');
        Imposter::close();
    }

    /**
     * @test
     */
    public function matchHeader()
    {
        Imposter::mock(8081)
            ->withHeaders(['key' => ['value']])
            ->returnHeaders(['key response' => 'value response'])
            ->once()
            ->send();

        $client   = new \GuzzleHttp\Client();
        $response = $client->post('http://localhost:8081', ['headers' => ['key' => 'value']]);
        self::assertSame($response->getHeader('key response'), ['value response']);
    }

    /**
     * @test
     */
    public function matchHeaders()
    {
        Imposter::mock(8081)
            ->withHeaders(['key 1' => ['value 1'], 'key 2' => ['value 2']])
            ->returnHeaders(['key response' => 'value response'])
            ->once()
            ->send();

        $client   = new \GuzzleHttp\Client();
        $response = $client->post('http://localhost:8081', ['headers' => ['key 1' => 'value 1', 'key 2' => 'value 2']]);
        self::assertSame($response->getHeader('key response'), ['value response']);
    }


    /**
     * @test
     */
    public function notMatchHeaders()
    {
        Imposter::mock(8081)
            ->withHeaders(['key 1' => ['value 1'], 'key 2' => ['value 2']])
            ->returnHeaders(['key response' => 'value response'])
            ->once()
            ->send();

        $client   = new \GuzzleHttp\Client();

        $e = null;
        try {
            $client->post('http://localhost:8081', ['headers' => ['key 1' => 'value 1', 'key 2' => 'value 3']]);
        } catch (\Exception $e) {

        }

        self::assertNotNull($e);
    }
}
