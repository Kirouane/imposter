<?php
declare(strict_types=1);

namespace Imposter;

use Imposter\Client\Imposter\MockBuilder;
use Imposter\Client\State;
use Imposter\Client\Http;
use Imposter\Common\Container;
use Psr\Container\ContainerInterface;

/**
 * Class Imposter
 * @package Imposter
 */
class Imposter
{
    /**
     * @var Imposter[]
     */
    private $httpImposters = [];

    /**
     * @var State
     */
    private $state;

    /**
     * @var Http
     */
    private $httpClient;
    /**
     * @var int
     */
    private $imposterPort;


    /**
     * Imposter constructor.
     * @param Container $di
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function __construct(int $imposterPort, \Imposter\Client\Http $httpClient)
    {
        $this->state = new State($httpClient);
        $this->httpClient = $httpClient;
        $this->imposterPort = $imposterPort;
    }

    /**
     * @param int $port
     * @return MockBuilder
     * @throws \Exception
     */
    public function mock(int $port): MockBuilder
    {
        return $this->httpImposters[] = new MockBuilder($port, $this->httpClient);
    }

    /**
     * @throws \Exception
     */
    public function close()
    {
        /** @var \Imposter\Client\Imposter\MockBuilder $imposter */
        foreach ($this->httpImposters as $imposter) {
            $imposter->resolve();
        }

        $this->destruct();
    }

    /**
     * @throws \Exception
     */
    public function shutdown()
    {
        $this->state->stop();
        $this->destruct();
    }

    public function destruct()
    {
        ImposterFactory::remove($this->imposterPort);
    }
}
