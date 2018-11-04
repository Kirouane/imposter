<?php
declare(strict_types=1);

namespace Imposter\Client\Imposter\Builder;

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
class Builder
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
     * @return Builder
     */
    public function withPath($value): Builder
    {
        $this->mock->setRequestUriPath($this->getConstraintFromValue($value));
        return $this;
    }

    /**
     * @param $value
     * @return Builder
     */
    public function withMethod($value): Builder
    {
        $this->mock->setRequestMethod($this->getConstraintFromValue($value));
        return $this;
    }

    /**
     * @param $value
     * @return Builder
     */
    public function withBody($value): Builder
    {
        $this->mock->setRequestBody($this->getConstraintFromValue($value));
        return $this;
    }

    /**
     * @param array $values
     * @return Builder
     */
    public function withHeaders(array $values): Builder
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
     * @return Builder
     */
    public function returnBody(string $responseBody): Builder
    {
        $this->mock->setResponseBody($responseBody);
        return $this;
    }

    /**
     * @param array $responseHeaders
     * @return Builder
     */
    public function returnHeaders(array $responseHeaders): Builder
    {

        $this->mock->setResponseHeaders($responseHeaders);
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
    public function times(int $times): Builder
    {
        $this->callTimePrediction = new Equals($times, $this->mock);
        return $this;
    }

    /**
     * @param int $times
     * @return Builder
     */
    public function atLeast(int $times): Builder
    {
        $this->callTimePrediction = new AtLeast($times, $this->mock);
        return $this;
    }

    /**
     * @param int $times
     * @return Builder
     */
    public function atMost(int $times): Builder
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
     * @return Builder
     */
    public function send(): Builder
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
    public function resolve(): Builder
    {
        $mock = $this->repository->find($this->mock);

        if ($this->callTimePrediction === null) {
            return $this;
        }

        $this->callTimePrediction->check($mock->getHits());

        return $this;
    }
}
