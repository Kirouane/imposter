<?php
declare(strict_types=1);

namespace Imposter;

use Imposter\Imposter\Prediction\CallTime\AbstractCallTime;
use Imposter\Imposter\Prediction\CallTime\AtLeast;
use Imposter\Imposter\Prediction\CallTime\AtMost;
use Imposter\Imposter\Prediction\CallTime\Equals;
use Imposter\Model\Mock;
use Imposter\Repository\HttpMock;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsIdentical;

/**
 * Class Imposter
 * @package Imposter
 */
class Imposter
{
    /**
     * @var
     */
    private static $initialized = false;

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

    /**
     * @param int $port
     * @return Imposter
     * @throws \Exception
     */
    public static function mock(int $port): Imposter
    {
        if (!self::$repository) {
            self::$repository = new HttpMock();
        }

        if (!self::$initialized) {
            self::$repository->drop();
            self::$initialized = true;
        }

        $instance          = new self($port);
        self::$imposters[] = $instance;

        return $instance;
    }

    /**
     * Imposter constructor.
     * @param int $port
     */
    private function __construct(int $port)
    {
        $this->mock = new Mock();
        $this->mock->setPort($port);
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
     * @return Imposter
     */
    public function withPath($value): Imposter
    {
        $this->mock->setRequestUriPath($this->getConstraintFromValue($value));
        return $this;
    }

    /**
     * @param $value
     * @return Imposter
     */
    public function withMethod($value): Imposter
    {
        $this->mock->setRequestMethod($this->getConstraintFromValue($value));
        return $this;
    }

    /**
     * @param $value
     * @return Imposter
     */
    public function withBody($value): Imposter
    {
        $this->mock->setRequestBody($this->getConstraintFromValue($value));
        return $this;
    }

    /**
     * @param string $responseBody
     * @return Imposter
     */
    public function returnBody(string $responseBody): Imposter
    {
        $this->mock->setResponseBody($responseBody);
        return $this;
    }

    /**
     * @return Imposter
     */
    public function once(): Imposter
    {
        return $this->times(1);
    }

    /**
     * @return Imposter
     */
    public function never(): Imposter
    {
        return $this->times(0);
    }

    /**
     * @return Imposter
     */
    public function twice(): Imposter
    {
        return $this->times(2);
    }

    /**
     * @param int $times
     * @return Imposter
     */
    public function times(int $times): Imposter
    {
        $this->callTimePrediction = new Equals($times, $this->mock);
        return $this;
    }

    /**
     * @param int $times
     * @return Imposter
     */
    public function atLeast(int $times): Imposter
    {
        $this->callTimePrediction = new AtLeast($times, $this->mock);
        return $this;
    }

    /**
     * @param int $times
     * @return Imposter
     */
    public function atMost(int $times): Imposter
    {
        $this->callTimePrediction = new AtMost($times, $this->mock);
        return $this;
    }

    /**
     * @return Imposter
     * @throws \Exception
     */
    public function send(): Imposter
    {
        $this->mock = self::$repository->insert($this->mock);
        return $this;
    }

    /**
     * @return Imposter
     * @throws \Exception
     */
    public function resolve(): Imposter
    {
        $mock = self::$repository->find($this->mock);

        if ($this->callTimePrediction === null) {
            return $this;
        }

        $this->callTimePrediction->check($mock->getHits());

        return $this;
    }

    /**
     * @throws \Exception
     */
    public static function close()
    {
        self::$initialized = false;
        /** @var Imposter $imposter */
        foreach (self::$imposters as $imposter) {
            $imposter->resolve();
        }
    }

    /**
     * @param HttpMock $repository
     */
    public static function setRepository(HttpMock $repository)
    {
        self::$repository = $repository;
    }

    /**
     * @return AbstractCallTime
     */
    public function getCallTimePrediction(): AbstractCallTime
    {
        return $this->callTimePrediction;
    }
}
