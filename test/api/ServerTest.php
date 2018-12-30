<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 12/18/18
 * Time: 5:24 PM
 */

namespace Imposter;


use PHPUnit\Framework\TestCase;

class ServerTest extends TestCase
{
    /**
     * @test
     */
    public function stop()
    {
        \Imposter\ImposterFactory::get()->destruct();
        $client   = new \GuzzleHttp\Client();
        $response = $client->get('http://localhost:2424/mock/log/html');
        self::assertSame(200, $response->getStatusCode());

        \Imposter\ImposterFactory::get()->shutdown();

        $e = null;

        try {
            $client->get('http://localhost:2424/mock/log/html');
        } catch (\GuzzleHttp\Exception\ConnectException $e) {}

        self:self::assertInstanceOf(\GuzzleHttp\Exception\ConnectException::class, $e);

    }
}