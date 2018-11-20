<?php
declare(strict_types=1);

namespace Imposter\Common;

use Imposter\Client\Console;
use Imposter\Client\Http;
use Imposter\Common\Di\InterfaceFactory;
use Imposter\Server;
use Imposter\Server\Log\LoggerFactory;
use Imposter\Server\Log\LogRepository;
use Imposter\Server\ViewFactory;

/**
 * Class Di
 * @package Interval
 */
class Di
{
    const DI = [
        //controllers,
        Server\Api\Controller\Mock\Post::class => [
            'server',
            'output',
            Server\Repository\Mock::class,
        ],

        Server\Api\Controller\Mock\Get::class => [
            Server\Repository\Mock::class,
            ViewFactory::class
        ],
        Server\Api\Controller\Mock\Delete::class => [
            Server\Repository\Mock::class,
        ],
        Server\Api\Controller\Match::class => [
            Server\Repository\Mock::class,
        ],

        Server\Api\Controller\Mock\Server\Delete::class => [
            'server'
        ],
        Server\Api\Controller\Mock\Log\Html\Get::class => [
            LogRepository::class,
            ViewFactory::class
        ],
        Server\Repository\Mock::class => [
            LoggerFactory::class
        ],
        Http::class => [
            \GuzzleHttp\Client::class,
            Console::class
        ]
    ];

    /**
     * @var array
     */
    private $services = [];

    /**
     * Instantiates and/or returns a service by its name
     * @param $name
     * @return mixed
     */
    public function get($name)
    {
        if (isset($this->services[$name])) {
            return $this->services[$name];
        }

        if (isset(self::DI[$name])) {
            /** @var array $argServicesName */
            $argServicesName = self::DI[$name];
            $args            = [];
            foreach ($argServicesName as $argServiceName) {
                $args[] = $this->get($argServiceName);
            }
            return $this->services[$name] = new $name(...$args);
        }

        $service = new $name();

        if ($service instanceof InterfaceFactory) {
            $service = $service->create($this);

        }

        return $this->services[$name] = $service;
    }

    /**
     * @param $name
     * @param $service
     */
    public function set($name, $service)
    {
        $this->services[$name] = $service;
    }
}
