<?php
declare(strict_types=1);

namespace Imposter\Api\Controller\Mock\Server;

use Imposter\Api\Controller\AbstractController;
use Psr\Http\Message\ServerRequestInterface;

/***
 * Class Delete
 * @package Imposter\Api\Controller\Mock\Server
 */
class Delete extends AbstractController
{
    /**
     * @var \Imposter\Server
     */
    private $server;


    /**
     * Delete constructor.
     * @param \Imposter\Server $server
     */
    public function __construct(\Imposter\Server $server)
    {
        $this->server     = $server;
    }

    /**
     * @param ServerRequestInterface $request
     * @return void
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $this->server->stop();
    }
}
