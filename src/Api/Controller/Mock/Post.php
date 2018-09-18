<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/3/18
 * Time: 6:17 PM
 */

namespace Imposter\Api\Controller\Mock;

use Imposter\Api\Controller\AbstractController;
use Imposter\Repository\Mock;
use Imposter\Server;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @param \React\Http\Server $server
     * @param \React\EventLoop\LoopInterface $loop
     * @param OutputInterface $output
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
     * @throws \Lazer\Classes\LazerException
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $body = $request->getBody()->getContents();
        $mock = unserialize($body, [\Imposter\Model\Mock::class]);

        if (!$mock instanceof \Imposter\Model\Mock) {
            $this->output->writeln('Invalid mock description :' . $body);
            throw new \Exception('Invalid body' . $body);
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

    private function createImposter($port)
    {
        $this->server->listen($port);
    }
}
