<?php
declare(strict_types=1);

namespace Imposter;

use Imposter\Api\Controller\Match;
use Imposter\Log\LoggerFactory;
use Monolog\Logger;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;
use React\Http\Response as HttpResponse;
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
     * @var Di
     */
    private $di;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * RouterMiddleware constructor.
     * @param Di $di
     */
    public function __construct(Di $di)
    {
        $this->di     = $di;
        $this->output = $di->get('output');
        $this->logger = $di->get(LoggerFactory::class);
    }

    /**
     * @param ServerRequestInterface $request
     * @return HttpResponse
     */
    public function __invoke(ServerRequestInterface $request)
    {
        return $this->getResponse($request);
    }

    /**
     * @param ServerRequestInterface $request
     * @return HttpResponse
     */
    private function getResponse(ServerRequestInterface $request): HttpResponse
    {
        try {

            $path = trim($request->getUri()->getPath(), '/');

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
     * @return HttpResponse
     */
    private function getMockResponse(string $path, ServerRequestInterface $request): Response
    {
        $arrayPath  = explode('/', $path);
        $arrayPath  = array_map('ucfirst', $arrayPath);
        $controller = 'Imposter\Api\Controller\\' . implode('\\', $arrayPath) . '\\' . ucfirst(strtolower($request->getMethod()));

        return ($this->di->get($controller))($request);
    }

    /**
     * @param ServerRequestInterface $request
     * @return Response
     */
    private function getMatchResponse(ServerRequestInterface $request): Response
    {
        return ($this->di->get(Match::class))($request);
    }
}
