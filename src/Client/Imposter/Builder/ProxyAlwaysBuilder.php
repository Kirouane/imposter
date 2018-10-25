<?php
declare(strict_types=1);

namespace Imposter\Client\Imposter\Builder;

use Imposter\Client\Http;
use Imposter\Client\Imposter\Prediction\CallTime\AbstractCallTime;
use Imposter\Client\Imposter\Prediction\CallTime\AtLeast;
use Imposter\Client\Imposter\Prediction\CallTime\AtMost;
use Imposter\Client\Imposter\Prediction\CallTime\Equals;
use Imposter\Common\Model\MockProxyAlways;

/**
 * Class ProxyAlwaysBuilder
 * @package Imposter\Client
 */
class ProxyAlwaysBuilder
{
    /**
     * @var \Imposter\Common\Model\MockProxyAlways
     */
    private $mock;

    /**
     * @var AbstractCallTime
     */
    private $callTimePrediction;

    /**
     * @var Http
     */
    private $repository;


    /**
     * Imposter constructor.
     * @param int $port
     * @param Http $repository
     */
    public function __construct(int $port, string $url, string $storePath, Http $repository)
    {
        $this->mock = new MockProxyAlways($port);
        $this->mock->setPort($port);
        $this->mock->setUrl($url);
        $this->mock->setStorePath($storePath);
        $this->repository = $repository;
    }

    /**
     * @param $value
     * @return Builder
     */
    public function withMethod(): ProxyAlwaysBuilder
    {
        $this->mock->setRequestMethod(true);
        return $this;
    }

    /**
     * @return Builder
     */
    public function once(): Builder
    {
        return $this->times(1);
    }

    /**
     * @return Builder
     */
    public function never(): Builder
    {
        return $this->times(0);
    }

    /**
     * @return Builder
     */
    public function twice(): Builder
    {
        return $this->times(2);
    }

    /**
     * @param int $times
     * @return Builder
     */
    public function times(int $times): ProxyAlwaysBuilder
    {
        $this->callTimePrediction = new Equals($times, $this->mock);
        return $this;
    }

    /**
     * @param int $times
     * @return Builder
     */
    public function atLeast(int $times): ProxyAlwaysBuilder
    {
        $this->callTimePrediction = new AtLeast($times, $this->mock);
        return $this;
    }

    /**
     * @param int $times
     * @return Builder
     */
    public function atMost(int $times): ProxyAlwaysBuilder
    {
        $this->callTimePrediction = new AtMost($times, $this->mock);
        return $this;
    }
    
    /**
     * @return \Imposter\Client\Imposter\Prediction\CallTime\AbstractCallTime
     */
    public function getCallTimePrediction(): AbstractCallTime
    {
        return $this->callTimePrediction;
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @return ProxyAlwaysBuilder
     * @throws \Exception
     */
    public function send(): ProxyAlwaysBuilder
    {
        $trace = debug_backtrace();
        $trace = reset($trace);

        $this->mock
            ->setFile($trace['file'])
            ->setLine($trace['line']);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->mock = $this->repository->insert($this->mock);
        return $this;
    }

    /**
     * @return Builder
     * @throws \Exception
     */
    public function resolve(): ProxyAlwaysBuilder
    {
        $mock = $this->repository->find($this->mock);

        if ($this->callTimePrediction === null) {
            return $this;
        }

        $this->callTimePrediction->check($mock->getHits());

        return $this;
    }
}
