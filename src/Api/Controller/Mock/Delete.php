<?php
declare(strict_types=1);

namespace Imposter\Api\Controller\Mock;

use Imposter\Api\Controller\AbstractController;
use Imposter\Repository\Mock;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;

/**
 * Class Delete
 * @package Imposter\Api\Controller\Mock
 */
class Delete extends AbstractController
{
    /**
     * @var Mock
     */
    private $repository;

    /**
     * Delete constructor.
     * @param Mock $repository
     */
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
                'Content-Type' => 'application/json',
            ]
        );
    }
}
