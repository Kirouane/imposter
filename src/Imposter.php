<?php
declare(strict_types=1);

namespace Imposter;

use Imposter\Repository\HttpMock;

/**
 * Class Imposter
 * @package Imposter
 */
class Imposter
{

    /**
     * @var Imposter[]
     */
    private static $httpImposters = [];

    /**
     * @var ImposterState
     */
    private static $state;

    /**
     * @param int $port
     * @return ImposterHttp
     * @throws \Exception
     */
    public static function mock(int $port): ImposterHttp
    {
        self::$state = self::$state ?: new ImposterState();
        self::$state->capture();
        return self::$httpImposters[] = new ImposterHttp($port, self::$state->getDi()->get(HttpMock::class));
    }

    /**
     * @throws \Exception
     */
    public static function close()
    {

        self::$state->release();
        /** @var ImposterHttp $imposter */
        foreach (self::$httpImposters as $imposter) {
            $imposter->resolve();
        }

        self::$httpImposters = [];
    }

}
