<?php
declare(strict_types=1);

namespace Imposter;

use Imposter\Client\ImposterHttp;
use Imposter\Client\ImposterState;
use Imposter\Client\Repository\HttpMock;

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
        self::init();
        return self::$httpImposters[] = new ImposterHttp($port, self::$state->getDi()->get(HttpMock::class));
    }


    /**
     *
     */
    public static function reset()
    {
        self::$httpImposters = [];
        self::init();
    }

    /**
     * @param int $port
     * @return void
     * @throws \Exception
     */
    public static function init()
    {
        self::$state = self::$state ?: new ImposterState();
        self::$state->capture();
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

    public static function shutdown()
    {
        self::$state = self::$state ?: new ImposterState();
        self::$state->stop();
    }
}
