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
     * @var bool
     */
    private $initialized = false;

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
    }

    /**
     * @throws \Exception
     */
    public function capture()
    {
        if (!$this->initialized) {
            if (!$this->httpClient->isStarted()) {
                $this->httpClient->restart();
            }
            $this->httpClient->drop();
            $this->initialized = true;
        }
    }

    public function release()
    {
        $this->initialized = false;
    }

    /**
     *
     */
    public function stop()
    {
        $this->release();
        if ($this->httpClient->isStarted()) {
            $this->httpClient->stop();
        }
    }

    /**
     * @return bool
     */
    public function isInitialized(): bool
    {
        return $this->initialized;
    }



}