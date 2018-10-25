<?php

namespace Imposter;

use PHPUnit\Framework\TestCase;

/**
 * Class ScenarioTest
 * @package Imposter
 */
class ProxyTest extends TestCase
{
    /**
     * @test
     */
    public function match()
    {
        Imposter::shutdown();


        Imposter::mockProxyAlways(8082, 'https://www.googleapis.com', 'test.json')
            ->withMethod()
            ->send();

        $client   = new \GuzzleHttp\Client();
        $response = \json_decode($client->get('http://localhost:8082/books/v1/volumes?q=test')->getBody()->getContents(), true);

        self::assertSame('books#volumes', $response['kind']);

    }

    public function tearDown()
    {
        //Imposter::close();
    }

}
