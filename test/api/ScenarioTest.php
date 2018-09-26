<?php

namespace Imposter;

use PHPUnit\Framework\Constraint\RegularExpression;
use PHPUnit\Framework\TestCase;

/**
 * Class ScenarioTest
 * @package Imposter
 */
class ScenarioTest extends TestCase
{
    use PhpunitTrait;

    /**
     * @test
     */
    public function match()
    {
        $this
            ->openImposter(8081)
            ->withPath('/users/1')
            ->withMethod(new RegularExpression('/GET|PUT|POST/'))
            ->returnBody('{"response" :"okay"}')
            ->once()
            ->send();

        $client   = new \GuzzleHttp\Client();
        $response = $client->post('http://localhost:8081/users/1')->getBody()->getContents();
        self::assertSame($response, '{"response" :"okay"}');
        $this->closeImposers();
    }

    /**
     * @test
     */
    public function notMatch()
    {
        $this
            ->openImposter(8081)
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
        $this
            ->openImposter(8081)
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
        $this
            ->openImposter(8081)
            ->withPath('/users/1')
            ->withMethod(new RegularExpression('/GET|PUT|POST/'))
            ->returnBody('{"response" :"1"}')
            ->once()
            ->send();

        $this
            ->openImposter(8081)
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
        $this->closeImposers();
    }

    /**
     * @test
     */
    public function matchHeader()
    {
        $this
            ->openImposter(8081)
            ->withHeaders(['key' => ['value']])
            ->returnHeaders(['key response' => 'value response'])
            ->once()
            ->send();

        $client   = new \GuzzleHttp\Client();
        $response = $client->post('http://localhost:8081', ['headers' => ['key' => 'value']]);
        self::assertSame($response->getHeader('key response'), ['value response']);
        $this->closeImposers();
    }

    /**
     * @test
     */
    public function matchHeaders()
    {
        $this
            ->openImposter(8081)
            ->withHeaders(['key 1' => ['value 1'], 'key 2' => ['value 2']])
            ->returnHeaders(['key response' => 'value response'])
            ->once()
            ->send();

        $client   = new \GuzzleHttp\Client();
        $response = $client->post('http://localhost:8081', ['headers' => ['key 1' => 'value 1', 'key 2' => 'value 2']]);
        self::assertSame($response->getHeader('key response'), ['value response']);
        $this->closeImposers();
    }


    /**
     * @test
     */
    public function notMatchHeaders()
    {
        $this
            ->openImposter(8081)
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
