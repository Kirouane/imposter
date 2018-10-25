<?php
declare(strict_types=1);

namespace Imposter;

use Imposter\Client\Imposter\Builder\Builder;
use Imposter\Client\Imposter\Builder\ProxyAlwaysBuilder;
use Imposter\Client\State;
use Imposter\Client\Http;

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
     * @var State
     */
    private static $state;

    /**
     * @var Common\Di
     */
    private static $di;

    /**
     * @param int $port
     * @return Builder
     * @throws \Exception
     */
    public static function mock(int $port): Builder
    {
        self::init();
        return self::$httpImposters[] = new Builder($port, self::getDi()->get(Http::class));
    }


    /**
     * @param int $port
     * @param $url
     * @param $file
     * @return ProxyAlwaysBuilder
     * @throws \Exception
     */
    public static function mockProxyAlways(int $port, $url, $file)
    {
        self::init();
        return self::$httpImposters[] = new ProxyAlwaysBuilder($port, $url, $file, self::getDi()->get(Http::class));
    }


    /**
     * @throws \Exception
     */
    public static function reset()
    {
        self::$httpImposters = [];
        self::init();
    }

    /**
     * @return void
     * @throws \Exception
     */
    public static function init()
    {
        self::$state = self::$state ?: new State(self::getDi()->get(Http::class));
        self::$state->capture();
    }

    /**
     * @throws \Exception
     */
    public static function close()
    {
        self::$state->release();
        /** @var Builder $imposter */
        foreach (self::$httpImposters as $imposter) {
            $imposter->resolve();
        }

        self::$httpImposters = [];
    }

    /**
     * @throws \Exception
     */
    public static function shutdown()
    {
        self::$state = self::$state ?: new State(self::getDi()->get(Http::class));
        self::$state->stop();
    }

    /**
     * @return Common\Di
     */
    public static function getDi(): Common\Di
    {
        if (self::$di) {
            return self::$di;
        }

        return self::$di = new Common\Di();
    }
}
