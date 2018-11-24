<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 11/20/18
 * Time: 1:32 PM
 */

namespace Imposter\Common;


use Imposter\Server\Api\Controller\Mock\Get;
use Imposter\Server\Api\Controller\Mock\Post;
use Imposter\Server\Log\Handler;
use Imposter\Server\Log\HtmlFormatter;
use Imposter\Server\Repository\Mock;
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
     * @param array|null $config
     * @throws \Exception
     */
    public function __construct()
    {
        if (self::$container === null) {
            $this->createContainer();
        }
    }

    /**
     * @param \DI\Container $container
     */
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

    public function set($key, $value)
    {
        self::$container->set($key, $value);
    }

    public function getConfig()
    {
        return [
            'logger' => function(Container $c) {
                $handler = new Handler($c->get(\Imposter\Server\Log\LogRepository::class));
                $handler->setFormatter(new HtmlFormatter());
                $log = new Logger('Imposter');
                $log->pushHandler($handler);

                return $log;
            },

            Post::class => \DI\create()->constructor(
                \DI\get('server'),
                \DI\get('output'),
                \DI\get(Mock::class)

            ),
            'view' => function() {
                $filesystemLoader = new FilesystemLoader([__DIR__ . '/../Api/Controller/%name%']);
                return new PhpEngine(new TemplateNameParser(), $filesystemLoader);
            },

            Mock::class => \DI\create()->constructor(\DI\get('logger')),

            Get::class => \DI\create()->constructor(\DI\get(Mock::class), \DI\get('view')),
        ];
    }
}