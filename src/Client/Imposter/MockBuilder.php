<?php
declare(strict_types=1);

namespace Imposter\Client\Imposter;

use Imposter\Client\Http;
use Imposter\Client\Imposter\Prediction\CallTime\AbstractCallTime;
use Imposter\Client\Imposter\Prediction\CallTime\AtLeast;
use Imposter\Client\Imposter\Prediction\CallTime\AtMost;
use Imposter\Client\Imposter\Prediction\CallTime\Equals;
use Imposter\Common\Model\Mock;
use PHPUnit\Framework\Constraint\ArraySubset;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsIdentical;

/**
 * Class Imposter
 * @package Imposter
 */
class MockBuilder
{
    /**
     * @var \Imposter\Common\Model\Mock
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
    public function __construct(int $port, Http $repository)
    {
        $this->mock = new Mock($port);
        $this->repository = $repository;
    }

    /**
     * @param $value
     * @return Constraint
     */
    private function getConstraintFromValue($value): Constraint
    {
        if (\is_object($value) && !$value instanceof Constraint) {
            throw new \InvalidArgumentException('the argument must be a scalar or an instance of ' . Constraint::class);
        }

        if (!$value instanceof Constraint) {
            $value = new IsIdentical($value);
        }

        return $value;
    }

    /**
     * @param $value
     * @return MockBuilder
     */
    public function withPath($value): MockBuilder
    {
        $this->mock->setRequestUriPath($this->getConstraintFromValue($value));
        return $this;
    }

    /**
     * @param $value
     * @return MockBuilder
     */
    public function withMethod($value): MockBuilder
    {
        $this->mock->setRequestMethod($this->getConstraintFromValue($value));
        return $this;
    }

    /**
     * @param $value
     * @return MockBuilder
     */
    public function withBody($value): MockBuilder
    {
        $this->mock->setRequestBody($this->getConstraintFromValue($value));
        return $this;
    }

    /**
     * @param array $values
     * @return MockBuilder
     */
    public function withHeaders(array $values): MockBuilder
    {
        $value = $values;
        if (!$values instanceof Constraint) {
            $value = new ArraySubset($values);
        }

        $this->mock->setRequestHeaders($value);
        return $this;
    }

    /**
     * @param string $responseBody
     * @return MockBuilder
     */
    public function returnBody(string $responseBody): MockBuilder
    {
        $this->mock->setResponseBody($responseBody);
        return $this;
    }

    /**
     * @param array $responseHeaders
     * @return MockBuilder
     */
    public function returnHeaders(array $responseHeaders): MockBuilder
    {

        $this->mock->setResponseHeaders($responseHeaders);
        return $this;
    }

    /**
     * @return MockBuilder
     */
    public function once(): MockBuilder
    {
        return $this->times(1);
    }

    /**
     * @return MockBuilder
     */
    public function never(): MockBuilder
    {
        return $this->times(0);
    }

    /**
     * @return MockBuilder
     */
    public function twice(): MockBuilder
    {
        return $this->times(2);
    }

    /**
     * @param int $times
     * @return MockBuilder
     */
    public function times(int $times): MockBuilder
    {
        $this->callTimePrediction = new Equals($times, $this->mock);
        return $this;
    }

    /**
     * @param int $times
     * @return MockBuilder
     */
    public function atLeast(int $times): MockBuilder
    {
        $this->callTimePrediction = new AtLeast($times, $this->mock);
        return $this;
    }

    /**
     * @param int $times
     * @return MockBuilder
     */
    public function atMost(int $times): MockBuilder
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
     * @return Mock
     */
    public function getMock(): Mock
    {
        return $this->mock;
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @return MockBuilder
     */
    public function send(): MockBuilder
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
     * @return MockBuilder
     * @throws \Exception
     */
    public function resolve(): MockBuilder
    {
        $mock = $this->repository->find($this->mock);

        if ($this->callTimePrediction === null) {
            return $this;
        }

        $this->callTimePrediction->check($mock->getHits());

        return $this;
    }
}
