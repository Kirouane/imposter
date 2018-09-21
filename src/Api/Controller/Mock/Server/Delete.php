<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/14/18
 * Time: 6:31 PM
 */

namespace Imposter\Api\Controller\Mock\Server;

use Imposter\Api\Controller\AbstractController;
use Imposter\Repository\Mock;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;
use Symfony\Component\Console\Output\OutputInterface;

/***
 * Class Delete
 * @package Imposter\Api\Controller\Mock\Server
 */
class Delete extends AbstractController
{
    /**
     * @var Mock
     */
    private $repository;

    /**
     * @var \Imposter\Server
     */
    private $server;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * Delete constructor.
     * @param \Imposter\Server $server
     * @param OutputInterface $output
     * @param Mock $repository
     */
    public function __construct(\Imposter\Server $server)
    {
        $this->server     = $server;
    }

    /**
     * @param ServerRequestInterface $request
     * @return Response
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $this->server->stop();
    }
}
