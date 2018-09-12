<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/13/18
 * Time: 12:54 PM
 */

namespace Imposter\Hydrator;


class Mock
{
    public function extract()
    {

    }

    public function hydrate(\Imposter\Model\Mock $mock, array $data)
    {
        return $mock
            ->setPort($data['port'])
            ->setRequestUriPath($data['request_uri_path'])
            ->setRequestBody($data['request_body'])
            ->setRequestMethod($data['request_method'])
            ->setResponseBody($data['response_body']);
    }
}