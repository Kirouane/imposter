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
use Imposter\Server\Log\JsonFormatter;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
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
    private $container;

    /**
     * Container constructor.
     * @throws \Exception
     */
    public function __construct()
    {
       $this->createContainer();
    }

    /**
     * @param $key
     * @return mixed
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function get($key)
    {
        return $this->container->get($key);
    }

    /**
     * @throws \Exception
     */
    private function createContainer()
    {
        $builder = new \DI\ContainerBuilder();
        $builder->addDefinitions($this->getConfig());
        $builder->useAutowiring(true);

        $this->container = $builder->build();
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->container->set($key, $value);
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'logger' => function(ContainerInterface $c) {
                $log = new Logger('Imposter');

                $handler = new Handler($c->get(\Imposter\Server\Log\LogRepository::class));
                $handler->setFormatter(new HtmlFormatter($c->get('view')));
                $log->pushHandler($handler);


                if ($c->get('config')->isFileLoggerEnabled()) {
                    $handler = new \Monolog\Handler\StreamHandler($c->get('config')->getLogFilePath());
                    $handler->setFormatter(new JsonFormatter(\Monolog\Formatter\JsonFormatter::BATCH_MODE_NEWLINES));
                    $log->pushHandler($handler);
                }

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
            ),
            'config' => function (ContainerInterface $c) {
                return new Config($c->has('config.path') ? require $c->get('config.path') : [], (int)$c->get('port'));
            },
            \Imposter\Client\Http::class =>  function (ContainerInterface $c) {
                $httpClient =  new \Imposter\Client\Http(
                    new \GuzzleHttp\Client([
                        'base_uri' => $c->get('config')->getUrl()
                    ]),
                    new \Imposter\Client\Console((int)$c->get('port'), $c->has('config.path') ? $c->get('config.path') : null)
                );

                $httpClient->setTimeout($c->get('config')->getServerTimeout());

                return $httpClient;
            },
        ];
    }
}