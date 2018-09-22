<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/6/18
 * Time: 6:17 PM
 */

namespace Imposter\Api\Controller;

use Imposter\Repository\Mock;

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
        $row = $this->repository->matchRequest($request);

        if ($row) {
            $row->setHits($row->getHits() + 1);
            $this->repository->update($row);

            return new Response(
                200,
                [
                    'Content-Type' => 'application/json',
                ],
                $row->getResponseBody()
            );
        }

        return new Response(
            404,
            [
                'Content-Type' => 'application/json',
            ],
            'Not found'
        );
    }
}
