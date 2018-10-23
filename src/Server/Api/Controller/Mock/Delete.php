<?php
declare(strict_types=1);

namespace Imposter\Server\Api\Controller\Mock;

use Imposter\Server\Api\Controller\AbstractController;
use Imposter\Server\Repository\Mock;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;

/**
 * Class Delete
 * @package Imposter\Api\Controller\Mock
 */
class Delete extends AbstractController
{
    /**
     * @var \Imposter\Server\Repository\Mock
     */
    private $repository;

    /**
     * Delete constructor.
     * @param \Imposter\Server\Repository\Mock $repository
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
