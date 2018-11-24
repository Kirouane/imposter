<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 11/24/18
 * Time: 5:17 PM
 */

namespace Imposter;


use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Templating\PhpEngine;

class ContainerTest extends TestCase
{
    /**
     * @test
     */
    public function get()
    {
        \Imposter\Common\Container::reset();
        $container = new \Imposter\Common\Container();
        self::assertInstanceOf(
            \Imposter\Client\Http::class,
            $container->get(\Imposter\Client\Http::class)
        );

        self::assertInstanceOf(
            Logger::class,
            $container->get('logger')
        );

        self::assertInstanceOf(
            PhpEngine::class,
            $container->get('view')
        );

    }

    /**
     * @test
     */
    public function set()
    {
        $container = new \Imposter\Common\Container();
        $container->set('key', 'value');
        self::assertSame('value', $container->get('key'));
    }
}