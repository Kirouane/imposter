<?php
declare(strict_types=1);

namespace Imposter\Client;

/**
 * Class State
 * @package Imposter\Client
 */
class State
{
    /**
     * @var Http
     */
    private $httpClient;


    /**
     * @param Http $httpClient
     */
    public function __construct(Http $httpClient)
    {
        $this->httpClient = $httpClient;
        if (!$this->httpClient->isStarted()) {
            $this->httpClient->restart();
        }

        $this->httpClient->drop();
    }

    /**
     *
     */
    public function stop()
    {
        if ($this->httpClient->isStarted()) {
            $this->httpClient->stop();
        }
    }
}