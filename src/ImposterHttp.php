<?php
declare(strict_types=1);

namespace Imposter;

use Imposter\Imposter\Prediction\CallTime\AbstractCallTime;
use Imposter\Imposter\Prediction\CallTime\AtLeast;
use Imposter\Imposter\Prediction\CallTime\AtMost;
use Imposter\Imposter\Prediction\CallTime\Equals;
use Imposter\Model\Mock;
use Imposter\Repository\HttpMock;
use PHPUnit\Framework\Constraint\ArraySubset;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsIdentical;

/**
 * Class Imposter
 * @package Imposter
 */
class ImposterHttp
{
    /**
     * @var Mock
     */
    private $mock;

    /**
     * @var AbstractCallTime
     */
    private $callTimePrediction;

    /**
     * @var HttpMock
     */
    private $repository;


    /**
     * Imposter constructor.
     * @param int $port
     * @param HttpMock $repository
     */
    public function __construct(int $port, HttpMock $repository)
    {
        $this->mock = new Mock();
        $this->mock->setPort($port);
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
     * @return ImposterHttp
     */
    public function withPath($value): ImposterHttp
    {
        $this->mock->setRequestUriPath($this->getConstraintFromValue($value));
        return $this;
    }

    /**
     * @param $value
     * @return ImposterHttp
     */
    public function withMethod($value): ImposterHttp
    {
        $this->mock->setRequestMethod($this->getConstraintFromValue($value));
        return $this;
    }

    /**
     * @param $value
     * @return ImposterHttp
     */
    public function withBody($value): ImposterHttp
    {
        $this->mock->setRequestBody($this->getConstraintFromValue($value));
        return $this;
    }

    /**
     * @param array $value
     * @return ImposterHttp
     */
    public function withHeaders(array $value): ImposterHttp
    {
        if (!$value instanceof Constraint) {
            $value = new ArraySubset($value);
        }


        $this->mock->setRequestHeaders($value);
        return $this;
    }

    /**
     * @param string $responseBody
     * @return ImposterHttp
     */
    public function returnBody(string $responseBody): ImposterHttp
    {
        $this->mock->setResponseBody($responseBody);
        return $this;
    }

    /**
     * @param array $responseHeaders
     * @return ImposterHttp
     */
    public function returnHeaders(array $responseHeaders): ImposterHttp
    {

        $this->mock->setResponseHeaders($responseHeaders);
        return $this;
    }

    /**
     * @return ImposterHttp
     */
    public function once(): ImposterHttp
    {
        return $this->times(1);
    }

    /**
     * @return ImposterHttp
     */
    public function never(): ImposterHttp
    {
        return $this->times(0);
    }

    /**
     * @return ImposterHttp
     */
    public function twice(): ImposterHttp
    {
        return $this->times(2);
    }

    /**
     * @param int $times
     * @return ImposterHttp
     */
    public function times(int $times): ImposterHttp
    {
        $this->callTimePrediction = new Equals($times, $this->mock);
        return $this;
    }

    /**
     * @param int $times
     * @return ImposterHttp
     */
    public function atLeast(int $times): ImposterHttp
    {
        $this->callTimePrediction = new AtLeast($times, $this->mock);
        return $this;
    }

    /**
     * @param int $times
     * @return ImposterHttp
     */
    public function atMost(int $times): ImposterHttp
    {
        $this->callTimePrediction = new AtMost($times, $this->mock);
        return $this;
    }
    
    /**
     * @return AbstractCallTime
     */
    public function getCallTimePrediction(): AbstractCallTime
    {
        return $this->callTimePrediction;
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @return ImposterHttp
     */
    public function send(): ImposterHttp
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
     * @return ImposterHttp
     * @throws \Exception
     */
    public function resolve(): ImposterHttp
    {
        $mock = $this->repository->find($this->mock);

        if ($this->callTimePrediction === null) {
            return $this;
        }

        $this->callTimePrediction->check($mock->getHits());

        return $this;
    }
}
