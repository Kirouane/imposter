<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/3/18
 * Time: 12:49 PM
 */

namespace Imposter;


use Imposter\Api\Controller\Match;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;
use Symfony\Component\Console\Output\OutputInterface;

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

    public function __construct(Di $di)
    {
        $this->di = $di;
        $this->output = $di->get('output');
    }

    public function __invoke(ServerRequestInterface $request)
    {
        return $this->getResponse($request);
    }

    private function getResponse(ServerRequestInterface $request)
    {
        try {
            $path = trim($request->getUri()->getPath(), '/');

            if (strpos(strtolower($path), 'mock') !== false) {
                return $this->getMockResponse($path, $request);
            }

            return $this->getMatchResponse($request);
        } catch (\Throwable $e) {
            $this->output->writeln($e);
            return new Response(
                400,
                [
                    'Content-Type' => 'application/json'
                ],
                json_encode([
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ])
            );
        }
    }

    private function getMockResponse($path, $request)
    {
        $path = explode('/', $path);
        $path = array_map('ucfirst', $path);
        $controller = 'Imposter\Api\Controller\\' . implode('\\', $path) . '\\' . ucfirst(strtolower($request->getMethod()));

        if (!class_exists($controller)) {
            return new Response(404);
        }

        return ($this->di->get($controller))($request);
    }

    private function getMatchResponse($request)
    {
        return ($this->di->get(Match::class))($request);
    }
}