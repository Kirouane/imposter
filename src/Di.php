<?php
declare(strict_types=1);

namespace Imposter;

use Imposter\Di\InterfaceFactory;
use Imposter\Log\LoggerFactory;
use Imposter\Log\LogRepository;

/**
 * Class Di
 * @package Interval
 */
class Di
{
    const DI = [
        //controllers,
        Api\Controller\Mock\Post::class => [
            'server',
            'output',
            Repository\Mock::class,
        ],

        Api\Controller\Mock\Get::class => [
            Repository\Mock::class,
            ViewFactory::class
        ],
        Api\Controller\Mock\Delete::class => [
            Repository\Mock::class,
        ],
        Api\Controller\Match::class => [
            Repository\Mock::class,
        ],

        Api\Controller\Mock\Server\Delete::class => [
            'server'
        ],
        Api\Controller\Mock\Log\Html\Get::class => [
            LogRepository::class,
            ViewFactory::class
        ],
        Repository\Mock::class => [
            LoggerFactory::class
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
