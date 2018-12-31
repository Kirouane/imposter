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
class PortTest extends TestCase
{
    use ImposterTrait;

    /**
     * @test
     */
    public function port()
    {
        ImposterFactory::get(2626)->shutdown();

        $this
            ->openImposter(8084, 2626)
            ->withPath('/test')
            ->send();

        $client   = new \GuzzleHttp\Client();
        $mocks = unserialize($client->get('http://localhost:2626/mock')->getBody()->getContents());
        self::assertInternalType('array', $mocks);
        self::assertCount(1, $mocks);
        self::assertContainsOnly(Mock::class, $mocks['8084']);
        ImposterFactory::get(2626)->shutdown();
    }
}
