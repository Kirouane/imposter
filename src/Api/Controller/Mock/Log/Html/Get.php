<?php
declare(strict_types=1);

namespace Imposter\Api\Controller\Mock\Log\Html;


use Imposter\Api\Controller\AbstractController;
use Imposter\Log\LogRepository;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;
use Symfony\Component\Templating\EngineInterface;

/***
 * Class Get
 * @package Imposter\Api\Controller\Mock\Log\Html
 */
class Get extends AbstractController
{
    /**
     * @var LogRepository
     */
    private $repository;

    /**
     * @var EngineInterface
     */
    private $view;

    /**
     * Get constructor.
     * @param LogRepository $repository
     * @param EngineInterface $view
     */
    public function __construct(LogRepository $repository, EngineInterface $view)
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
        $logs = $this->repository->getAll();
        rsort($logs);

        return new Response(
            200,
            [
                'Content-Type' => 'text/html',
            ],
            $this->view->render(__DIR__ . '/get.phtml', ['logs' => $logs])
        );
    }
}