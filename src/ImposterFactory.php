<?php
declare(strict_types=1);

namespace Imposter;

use Imposter\Client\Http;
use Imposter\Common\Config;
use Imposter\Common\Container;

/**
 * Class Imposter
 * @package Imposter
 */
class ImposterFactory
{
    /**
     * @var array
     */
    private static $instances = [];

    /**
     * @param string|null $configPath
     * @throws \Exception
     */
    public static function create(int $port, string $configPath = null)
    {
        if ($configPath && !is_file($configPath)) {
            throw new \InvalidArgumentException("The file $configPath doesn't exist.");
        }

        $container = new Container();

        if ($configPath) {
            $container->set('config.path', realpath($configPath));
        }

        $container->set('port', $port);
        self::$instances[$port] = new Imposter($port, $container->get(Http::class));
    }

    /**
     * @param int $imposterPort
     * @return Imposter
     * @throws \Exception
     */
    public static function get($imposterPort = Config::DEFAULT_PORT)
    {
        if (isset(self::$instances[$imposterPort])) {
            return self::$instances[$imposterPort];
        }

        self::create($imposterPort);
        return self::$instances[$imposterPort];
    }

    /**
     * @param int $imposterPort
     */
    public static function remove($imposterPort = Config::DEFAULT_PORT)
    {
        if (!isset(self::$instances[$imposterPort])) {
            return;
        }

        $imposter = self::$instances[$imposterPort];

        unset(self::$instances[$imposterPort], $imposter);
    }
}
