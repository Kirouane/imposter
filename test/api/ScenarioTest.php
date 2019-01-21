<?php

namespace Imposter;

use Imposter\Common\Constraint\ArrayRegularExpression;
use Imposter\Common\Constraint\JsonRegularExpression;
use PHPUnit\Framework\Constraint\ArraySubset;
use PHPUnit\Framework\Constraint\JsonMatches;
use PHPUnit\Framework\Constraint\RegularExpression;
use PHPUnit\Framework\TestCase;

/**
 * Class ScenarioTest
 * @package Imposter
 */
class ScenarioTest extends TestCase
{
    use ImposterTrait;

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
    public function matchBody()
    {
        $this
            ->openImposter(8081)
            ->withBody(new JsonRegularExpression('{"test":"[a-z]{4}"}'))
            ->returnBody('{"response" :"okay"}')
            ->once()
            ->send();

        $client   = new \GuzzleHttp\Client();
        $response = $client->post('http://localhost:8081/users/1', ['body' => '{"test":"abcd"}'])->getBody()->getContents();
        self::assertSame($response, '{"response" :"okay"}');
        $this->closeImposers();
    }

    /**
     * @test
     */
    public function matchNotBody()
    {
        $this
            ->openImposter(8081)
            ->withBody(new JsonRegularExpression('{"test":"[a-z]{4}"}'))
            ->returnBody('{"response" :"okay"}')
            ->once()
            ->send();

        $client   = new \GuzzleHttp\Client();

        $e = null;
        try {
            $client->post('http://localhost:8081/users/1', ['body' => '{"test":"abc"}'])->getBody()->getContents();
        } catch (\Exception $e) {

        }

        self::assertNotNull($e);
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
            $client->get('http://localhost:8081/users/1')->getBody()->getContents();
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
            $this->closeImposers();
        } catch (\Exception $e) {

        }


        \Imposter\ImposterFactory::get()->destruct();

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
    public function matchMultipleMockWithBody()
    {
        $this
            ->openImposter(8081)
            ->withBody('{"a":"1"}')
            ->returnBody('{"response" :"1"}')
            ->once()
            ->send();

        $this
            ->openImposter(8081)
            ->withBody('{"a":"2"}')
            ->returnBody('{"response" :"2"}')
            ->once()
            ->send();


        $client   = new \GuzzleHttp\Client();
        $response = $client->post('http://localhost:8081', ['body' => '{"a":"1"}'])->getBody()->getContents();
        self::assertSame($response, '{"response" :"1"}');
        $response = $client->post('http://localhost:8081', ['body' => '{"a":"2"}'])->getBody()->getContents();
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
        \Imposter\ImposterFactory::get()->destruct();
    }

    /**
     * @test
     */
    public function returnStatusHeader()
    {
        $this
            ->openImposter(8081)
            ->returnHeaders(['status' => 204])
            ->once()
            ->send();

        $client   = new \GuzzleHttp\Client();
        $response = $client->get('http://localhost:8081');
        self::assertSame($response->getStatusCode(), 204);
        $this->closeImposers();
    }


    /**
     * @test
     */
    public function matchFormUrlencoded()
    {
        $this
            ->openImposter(8081)
            ->withHeaders(['Content-Type' => ['application/x-www-form-urlencoded']])
            ->withBody(new ArrayRegularExpression(['a' => 'b']))
            ->returnBody('{"response" :"OK"}')
            ->once()
            ->send();

        $client   = new \GuzzleHttp\Client();
        $response = $client->request(
            'POST',
            'http://localhost:8081',
            [
                'form_params' => ['a' => 'b', 'c' => 'd']
            ]
        )->getBody()->getContents();

        self::assertSame($response, '{"response" :"OK"}');
        $this->closeImposers();
    }
}
