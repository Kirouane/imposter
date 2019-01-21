<?php
declare(strict_types=1);

namespace Imposter\Server;

use Imposter\Common\Container;
use Imposter\Server\Api\Controller\Match;
use Monolog\Logger;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RouterMiddleware
 * @package Imposter
 */
class RouterMiddleware
{
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var Container
     */
    private $di;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * RouterMiddleware constructor.
     * @param Container $di
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function __construct(Container $di)
    {
        $this->di     = $di;
        $this->output = $di->get('output');
        $this->logger = $di->get('logger');
    }

    /**
     * @param ServerRequestInterface $request
     * @return Response
     */
    public function __invoke(ServerRequestInterface $request)
    {
        return $this->getResponse($request);
    }

    /**
     * @param ServerRequestInterface $request
     * @return Response
     */
    private function getResponse(ServerRequestInterface $request): Response
    {
        try {

            $path = trim($request->getUri()->getPath(), '/');
            $this->logger->info("Receiving request $path");
            if (stripos($path, 'mock') !== false) {
                return $this->getMockResponse($path, $request);
            }

            return $this->getMatchResponse($request);
        } catch (\Throwable $e) {
            $this->logger->critical($e);
            $this->output->writeln($e->getMessage());
            return new Response(
                400,
                [
                    'Content-Type' => 'application/json',
                ],
                json_encode([
                    'message' => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'    => $e->getLine(),
                    'trace'   => $e->getTraceAsString(),
                ])
            );
        }
    }

    /**
     * @param $path
     * @param $request
     * @return Response
     */
    private function getMockResponse(string $path, ServerRequestInterface $request): Response
    {
        $arrayPath  = explode('/', $path);
        $arrayPath  = array_map('ucfirst', $arrayPath);
        $controller = 'Imposter\Server\Api\Controller\\' . implode('\\', $arrayPath) . '\\' . ucfirst(strtolower($request->getMethod()));
        $this->logger->info("Routing to $controller");

        return ($this->di->get($controller))($request);
    }

    /**
     * @param ServerRequestInterface $request
     * @return Response
     */
    private function getMatchResponse(ServerRequestInterface $request): Response
    {
        $this->logger->info('Routing to the ' . Match::class . ' to match the request.');
        return ($this->di->get(Match::class))($request);
    }
}
