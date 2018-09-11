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
        $row = $this->repository->findById($request->getQueryParams()['id']);
        return new Response(
            200,
            [
                'Content-Type' => 'application/json'
            ], $row->hits
        );
    }
}