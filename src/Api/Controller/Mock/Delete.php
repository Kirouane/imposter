<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/14/18
 * Time: 6:31 PM
 */

namespace Imposter\Api\Controller\Mock;


use Imposter\Api\Controller\AbstractController;
use Imposter\Repository\Mock;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;

class Delete extends AbstractController
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
        $this->repository->drop();

        return new Response(
            200,
            [
                'Content-Type' => 'application/json'
            ]
        );
    }
}