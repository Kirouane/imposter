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
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;
use Symfony\Component\Templating\EngineInterface;

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
     * @param $format
     */
    private function render(array $rows, $format)
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
