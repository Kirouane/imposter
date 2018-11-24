<?php
declare(strict_types=1);

namespace Imposter\Server;

use Imposter\Common\Container;
use React\Http\Server as ReactServer;

/**
 * Class Server
 * @package Imposter
 */
class Server
{
    const HOST     = 'localhost';
    const PROTOCOL = 'http';
    const PORT     = 2424;

    const URL = self::PROTOCOL . '://' . self::HOST . ':' . self::PORT;

    /**
     * @var Container
     */
    private $di;

    /**
     * @var array
     */
    private $sockets = [];

    /**
     * @var ReactServer
     */
    private $reactServer;

    /**
     * @var \React\EventLoop\LoopInterface
     */
    private $loop;

    /**
     * Server constructor.
     * @param Container $di
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function __construct(Container $di)
    {
        $this->di          = $di;
        $this->loop        = \React\EventLoop\Factory::create();
        $this->reactServer = new ReactServer(new RouterMiddleware($this->di));
        $this->di->set('server', $this);
    }

    /**
     * @param int $port
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function run(int $port)
    {
        if (isset($this->sockets[$port])) {
            $this->di->get('output')->writelin("$port already in use.");
            return;
        }

        $this->listen($port);
        $this->loop->run();
    }

    /**
     * @param int $port
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function listen(int $port)
    {
        if (isset($this->sockets[$port])) {
            $this->di->get('output')->writeln("$port already in use.");
            return;
        }

        $socket               = new \React\Socket\Server($port, $this->loop);
        $this->sockets[$port] = $socket;
        $this->reactServer->listen($socket);
    }

    public function stop()
    {
        $this->loop->stop();
    }
}
