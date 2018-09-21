<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/21/18
 * Time: 12:59 PM
 */

namespace Imposter\Api\Controller\Mock\Log\Html;


use Imposter\Api\Controller\AbstractController;
use Imposter\Log\LogRepository;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;

class Get extends AbstractController
{
    /**
     * @var LogRepository
     */
    private $repository;

    /**
     * Get constructor.
     * @param LogRepository $repository
     */
    public function __construct(LogRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @param ServerRequestInterface $request
     * @return Response
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $logs = $this->repository->getAll();

        return new Response(
            200,
            [
                'Content-Type' => 'text/html',
            ],
            implode('', $logs)
        );
    }
}