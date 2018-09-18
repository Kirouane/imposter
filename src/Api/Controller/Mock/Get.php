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
class Get extends AbstractController
{

    /**
     * @var Mock
     */
    private $repository;


    public function __construct(Mock $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @param ServerRequestInterface $request
     * @return Response
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $id = $request->getQueryParams()['id'] ?? null;

        if ($id) {
            return $this->getOne($id);
        }

        return $this->getAll();

    }

    /**
     * @return Response
     */
    private function getAll(): Response
    {
        $rows = $this->repository->findAll();

        return new Response(
            200,
            [
                'Content-Type' => 'application/json'
            ], serialize($rows)
        );
    }

    /**
     * @param $id
     * @return Response
     */
    private function getOne($id): Response
    {
        $row = $this->repository->findById($id);

        return new Response(
            200,
            [
                'Content-Type' => 'application/json'
            ], serialize($row)
        );
    }
}