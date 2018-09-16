<?php

namespace Imposter;

use Imposter\Imposter\Prediction\CallTime\AbstractCallTime;
use Imposter\Imposter\Prediction\CallTime\AtLeast;
use Imposter\Imposter\Prediction\CallTime\AtMost;
use Imposter\Imposter\Prediction\CallTime\Equals;
use Imposter\Model\Mock;
use Imposter\Repository\HttpMock;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsIdentical;

class Imposter
{
    /**
     * @var int
     */
    private $port;

    /**
     * @var int
     */
    private $times;

    /**
     * @var Mock
     */
    private $mock;

    /**
     * @var HttpMock
     */
    private static $repository;

    /**
     * @var Imposter[]
     */
    private static $imposters = [];

    /**
     * @var AbstractCallTime
     */
    private $callTimePrediction;


    public static function mock(int $port): Imposter
    {
        if (!self::$repository) {
            self::$repository = new HttpMock();
        }

        $instance = new self($port);
        self::$imposters[] = $instance;

        return $instance;
    }

    private function __construct(int $port)
    {
        $this->mock = new Mock();
        $this->mock->setPort($port);
        $this->port = $port;
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

    public function withPath($value): Imposter
    {
        $this->mock->setRequestUriPath($this->getConstraintFromValue($value));
        return $this;
    }

    public function withMethod($value): Imposter
    {
        $this->mock->setRequestMethod($this->getConstraintFromValue($value));
        return $this;
    }

    public function withBody($value): Imposter
    {
        $this->mock->setRequestBody($this->getConstraintFromValue($value));
        return $this;
    }

    public function returnBody(string $responseBody): Imposter
    {
        $this->mock->setResponseBody($responseBody);
        return $this;
    }

    public function once(): Imposter
    {
        return $this->times(1);
    }

    public function never(): Imposter
    {
        return $this->times(0);
    }

    public function twice(): Imposter
    {
        return $this->times(2);
    }

    public function times($times): Imposter
    {
        $this->callTimePrediction = new Equals($times, $this->mock);
        return $this;
    }

    public function atLeast($times): Imposter
    {
        $this->callTimePrediction = new AtLeast($times, $this->mock);
        return $this;
    }


    public function atMost($times): Imposter
    {
        $this->callTimePrediction = new AtMost($times, $this->mock);
        return $this;
    }

    public function send(): Imposter
    {
        $this->mock = self::$repository->insert($this->mock);
        return $this;
    }

    public function resolve()
    {
        $mock = self::$repository->find($this->mock);

        if ($this->callTimePrediction === null) {
            return $this;
        }

        $this->callTimePrediction->check($mock->getHits());

        return $this;
    }

    public static function close()
    {
        /** @var Imposter $imposter */
        foreach (self::$imposters as $imposter) {
            $imposter->resolve();
        }

        self::$repository->drop();
    }
}