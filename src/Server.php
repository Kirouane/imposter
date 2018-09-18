<?php
declare(strict_types=1);

namespace Imposter;

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
     * @var Di
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
     * @var \Imposter\RouterMiddleware
     */
    private $router;

    /**
     * Server constructor.
     * @param Di $di
     */
    public function __construct(Di $di)
    {
        $this->di          = $di;
        $this->loop        = \React\EventLoop\Factory::create();
        $this->router      =  new RouterMiddleware($this->di);
        $this->reactServer = new ReactServer($this->router);
        $this->di->set('server', $this);
    }

    /**
     * @param int $port
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
}
