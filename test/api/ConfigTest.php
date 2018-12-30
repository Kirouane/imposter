<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 12/30/18
 * Time: 2:29 PM
 */

namespace Imposter;


use PHPUnit\Framework\Constraint\RegularExpression;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /**
     * @test
     * @runInSeparateProcess
     * @preserveGlobalState disabled*
     */
    public function port()
    {
        Imposter::setConfigPath('config.php');
        \Imposter\Imposter::shutdown();

        Imposter::mock(8083)
            ->withPath('/users/1')
            ->withMethod(new RegularExpression('/POST|PUT/'))
            ->returnBody('{"response" :"okay"}')
            ->twice()
            ->send();

        $client   = new \GuzzleHttp\Client();
        $response = $client->post('http://localhost:8083/users/1')->getBody()->getContents();
        self::assertSame($response, '{"response" :"okay"}');

    }
}