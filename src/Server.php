<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/11/18
 * Time: 12:43 PM
 */

namespace Imposter;



use Imposter\Repository\Mock;
use React\Http\Server as ReactServer;

class Server
{
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
     * Server constructor.
     * @param Di $di
     */
    public function __construct(Di $di)
    {
        $this->di = $di;
        $this->loop = \React\EventLoop\Factory::create();
        $this->router =  new \Imposter\RouterMiddleware($this->di);
        $this->reactServer = new ReactServer($this->router);
        $this->di->set('server', $this);
    }

    public function run($port): void
    {
        if (isset($this->sockets[$port])) {
            $this->di->get('output')->writelin("$port already in use.");
            return;
        }

        $this->listen($port);
        $this->loop->run();
    }

    /**
     * @param $port
     */
    public function listen($port): void
    {
        if (isset($this->sockets[$port])) {
            $this->di->get('output')->writeln("$port already in use.");
            return;
        }

        $socket = new \React\Socket\Server($port, $this->loop);
        $this->sockets[$port] = $socket;
        $this->reactServer->listen($socket);
    }
}