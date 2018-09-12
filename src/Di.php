<?php
declare(strict_types=1);

namespace Imposter;

use Imposter\Db;

/**
 * Class Di
 * @package Interval
 */
class Di
{   const DI = [
        //controllers,
        \Imposter\Api\Controller\Mock\Post::class => [
            'server',
            'output',
            Repository\Mock::class
        ],

        \Imposter\Api\Controller\Mock\Get::class => [
            Repository\Mock::class
        ],

        Repository\Mock::class => [
            \Imposter\Db::class
        ],
        \Imposter\Api\Controller\Match::class => [
            Repository\Mock::class
        ],
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

        return $this->services[$name] = new $name();
    }

    public function set($name, $service)
    {
        $this->services[$name] = $service;
    }
}
