<?php
declare(strict_types=1);

namespace Imposter\Api\Controller\Mock;

use Imposter\Api\Controller\AbstractController;
use Imposter\Repository\Mock;
use Imposter\Server;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Post
 * @package Imposter\Api\Controller\Mock
 */
class Post extends AbstractController
{
    /**
     * @var Server
     */
    private $server;

    /**
     * @var OutputInterface
     */
    private $output;
    /**
     * @var Mock
     */
    private $repository;

    /**
     * Post constructor.
     * @param Server $server
     * @param OutputInterface $output
     * @param Mock $repository
     */
    public function __construct(Server $server, OutputInterface $output, Mock $repository)
    {
        $this->server     = $server;
        $this->output     = $output;
        $this->repository = $repository;
    }

    /**
     * @param ServerRequestInterface $request
     * @return Response
     * @throws \Exception
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $body = $request->getBody()->getContents();
        $mock = unserialize($body, [\Imposter\Model\Mock::class]);

        if (!$mock instanceof \Imposter\Model\Mock) {
            $this->output->writeln('Invalid mock description :' . $body);
            throw new \RuntimeException('Invalid body' . $body);
        }

        $mock = $this->repository->insert($mock);
        $this->createImposter($mock->getPort());

        return new Response(
            200,
            [
                'Content-Type' => 'application/json',
            ],
            serialize($mock)
        );
    }

    /**
     * @param $port
     */
    private function createImposter($port)
    {
        $this->server->listen($port);
    }
}
