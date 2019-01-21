<?php
declare(strict_types=1);

namespace Imposter\Server\Api\Controller;

use Imposter\Server\Repository\Mock;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;

/**
 * Class Match
 * @package Imposter\Api\Controller
 */
class Match extends AbstractController
{
    /**
     * @var Mock
     */
    private $repository;

    /**
     * Match constructor.
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
        $mock = $this->repository->matchRequest($request);

        return $mock ? $this->getMockResponse($mock) : $this->getNotFoundMockResponse();
    }

    private function getNotFoundMockResponse(): Response
    {
        return new Response(
            404,
            [
                'Content-Type' => 'application/json',
            ],
            'Mock not found.'
        );
    }

    private function getMockResponse(\Imposter\Common\Model\Mock $mock): Response
    {
        $mock->hit();
        $this->repository->update($mock);

        return new Response(
            $mock->getResponseHeaders()['status'] ?? 200,
            $mock->getResponseHeaders(),
            $mock->getResponseBody()
        );
    }
}
