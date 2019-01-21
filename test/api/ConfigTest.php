<?php

namespace Imposter;

use Imposter\Common\Model\Mock;
use Imposter\ImposterFactory;
use PHPUnit\Framework\Constraint\RegularExpression;
use PHPUnit\Framework\TestCase;

/**
 * Class PortTest
 * @package Imposter
 */
class ConfigTest extends TestCase
{
    use ImposterTrait;

    /**
     * @test
     */
    public function logPath()
    {
        $logFile = __DIR__ . '/config-test.log';
        $this->createImposter(__DIR__ . '/config.php');
        $this
            ->openImposter(8081)
            ->withPath('/users/1')
            ->withMethod(new RegularExpression('/GET|PUT|POST/'))
            ->returnBody('{"response" :"okay"}')
            ->send();

        $client   = new \GuzzleHttp\Client();
        try {

            $client->post('http://localhost:8081/users/2')->getBody()->getContents();
        } catch (\Exception $e) {

        }
        $this->closeImposers();
        self::assertFileExists($logFile);
        unlink($logFile);
    }
}
