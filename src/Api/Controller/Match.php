<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/6/18
 * Time: 6:17 PM
 */

namespace Imposter\Api\Controller;
use Imposter\Repository\Mock;
use Lazer\Classes\Database as Lazer;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;

class Match extends AbstractController
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
        $row = $this->repository->findByCriteria([
            'request_uri_path' => trim($request->getUri()->getPath(), '/')
        ]);
        //$row = Lazer::table('mock')->where('request_uri_path', '=', trim($request->getUri()->getPath(), '/'))->find();
        if ($row->count()) {
            $row->hits = $row->hits + 1;
            $row->save();
            return new Response(
                200,
                [
                    'Content-Type' => 'application/json'
                ],$row->response_body
            );
        }

        return new Response(
            404,
            [
                'Content-Type' => 'application/json'
            ], 'Not found'
        );
    }
}