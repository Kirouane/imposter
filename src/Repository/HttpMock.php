<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/13/18
 * Time: 1:47 PM
 */

namespace Imposter\Repository;



class HttpMock
{
    /**
     * @var \Guzzle\Http\Client
     */
    private $client;

    public function __construct()
    {

        $this->client = new \Guzzle\Http\Client();
    }

    public function insert(\Imposter\Model\Mock $mock)
    {
        $request = $this->client->post(
            'http://localhost:8080/mock',
            null,
            serialize($mock)
        );

        $body = $this->client->send($request)->getBody(true);

        return unserialize($body, [\Imposter\Model\Mock::class]);
    }

    public function find(\Imposter\Model\Mock $mock)
    {
        $request = $this->client->get(
            'http://localhost:' . $mock->getPort(). '/mock',
            null
        );
        $request->getQuery()->set('id', $mock->getId());
        $body = $this->client->send($request)->getBody(true);

        /** @var \Imposter\Model\Mock $mock */
        return unserialize($body, [\Imposter\Model\Mock::class]);
    }


    public function drop()
    {
        $request = $this->client->delete(
            'http://localhost:8080/mock',
            null
        );

        $this->client->send($request);
    }
}