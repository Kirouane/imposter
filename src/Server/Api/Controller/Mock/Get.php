<?php
declare(strict_types=1);

namespace Imposter\Server\Api\Controller\Mock;

use Imposter\Server\Api\Controller\AbstractController;
use Imposter\Server\Repository\Mock;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;
use Symfony\Component\Templating\EngineInterface;

/**
 * Class Get
 * @package Imposter\Api\Controller\Mock
 */
class Get extends AbstractController
{
    /**
     * @var \Imposter\Server\Repository\Mock
     */
    private $repository;

    /**
     * @var EngineInterface
     */
    private $view;

    /**
     * Get constructor.
     * @param Mock $repository
     * @param EngineInterface $view
     */
    public function __construct(Mock $repository, EngineInterface $view)
    {
        $this->repository = $repository;
        $this->view = $view;
    }

    /**
     * @param ServerRequestInterface $request
     * @return Response
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $id = $request->getQueryParams()['id'] ?? null;
        $port = $request->getQueryParams()['port'] ?? null;

        if ($id) {
            return $this->getOne((int)$port, $id, $request);
        }

        return $this->getAll($request);
    }

    /**
     * @param ServerRequestInterface $request
     * @return Response
     */
    private function getAll(ServerRequestInterface $request): Response
    {
        $rows = $this->repository->findAll();
        $format = $request->getQueryParams()['format'] ?? null;
        if ($format) {
            return $this->render($rows, $format);
        }

        return new Response(
            200,
            [
                'Content-Type' => 'text/html',
            ],
            serialize($rows)
        );
    }

    /**
     * @param int $port
     * @param $id
     * @param ServerRequestInterface $request
     * @return Response
     */
    private function getOne(int $port, $id, ServerRequestInterface $request): Response
    {
        $row = $this->repository->findById($port, $id);
        $format = $request->getQueryParams()['format'] ?? null;

        if ($format) {
            return $this->render([$row], $format);
        }

        return new Response(
            200,
            [
                'Content-Type' => 'text/html',
            ],
            serialize($row)
        );
    }

    /**
     * @param array $rows
     * @param string $format
     * @return Response
     */
    private function render(array $rows, string $format = null): Response
    {
        return new Response(
            200,
            [
                'Content-Type' => 'application/json',
            ],

            json_encode($rows, JSON_PRETTY_PRINT)
        );

    }
}
