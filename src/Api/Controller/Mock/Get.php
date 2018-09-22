<?php
declare(strict_types=1);

namespace Imposter\Api\Controller\Mock;

use Imposter\Api\Controller\AbstractController;
use Imposter\Repository\Mock;
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
     * @var Mock
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

        if ($id) {
            return $this->getOne($id, $request);
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
     * @param $id
     * @param ServerRequestInterface $request
     * @return Response
     */
    private function getOne($id, ServerRequestInterface $request): Response
    {
        $row = $this->repository->findById($id);
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
