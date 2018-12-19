<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 11/20/18
 * Time: 1:32 PM
 */

namespace Imposter\Common;

use Imposter\Server\Log\Handler;
use Imposter\Server\Log\HtmlFormatter;
use Monolog\Logger;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;

/**
 * Class Container
 * @package Imposter\Common
 */
class Container
{
    /**
     * @var \DI\Container
     */
    private static $container;

    /**
     * Container constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        if (self::$container === null) {
            $this->createContainer();
        }
    }

    public static function reset()
    {
        self::$container = null;
    }

    /**
     * @param $key
     * @return mixed
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function get($key)
    {
        return self::$container->get($key);
    }

    /**
     * @throws \Exception
     */
    private function createContainer()
    {
        $builder = new \DI\ContainerBuilder();
        $builder->addDefinitions($this->getConfig());
        $builder->useAutowiring(true);

        self::$container = $builder->build();
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        self::$container->set($key, $value);
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'logger' => function(Container $c) {
                $handler = new Handler($c->get(\Imposter\Server\Log\LogRepository::class));
                $handler->setFormatter(new HtmlFormatter($c->get('view')));
                $log = new Logger('Imposter');
                $log->pushHandler($handler);

                return $log;
            },
            \Imposter\Server\Api\Controller\Mock\Post::class => \DI\create()->constructor(
                \DI\get('server'),
                \DI\get('output'),
                \DI\get(\Imposter\Server\Repository\Mock::class)

            ),
            'view' => function() {
                $filesystemLoader = new FilesystemLoader([__DIR__ . '/../Api/Controller/%name%']);
                return new PhpEngine(new TemplateNameParser(), $filesystemLoader);
            },
            \Imposter\Server\Repository\Mock::class => \DI\create()->constructor(\DI\get('logger')),

            \Imposter\Server\Api\Controller\Mock\Get::class => \DI\create()->constructor(
                \DI\get(\Imposter\Server\Repository\Mock::class),
                \DI\get('view')
            ),
            \Imposter\Server\Api\Controller\Mock\Log\Html\Get::class => \DI\create()->constructor(
                \DI\get(\Imposter\Server\Log\LogRepository::class),
                \DI\get('view')
            ),
            \Imposter\Server\Api\Controller\Mock\Delete::class => \DI\create()->constructor(
                \DI\get(\Imposter\Server\Repository\Mock::class)
            ),
            \Imposter\Server\Api\Controller\Mock\Server\Delete::class =>  \DI\create()->constructor(
                \DI\get('server')
            )
        ];
    }
}