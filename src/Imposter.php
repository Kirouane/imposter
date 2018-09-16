<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/5/18
 * Time: 1:44 PM
 */

namespace Imposter;


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

    private static $imposters = [];

    private $timesComparison = '===';

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
        $this->times = 1;
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

        if ($this->times === null) {
            return $this;
        }

        $assertion = '$assertion=(' . $this->times . $this->timesComparison . (int)$mock->getHits() . ');';
        eval($assertion);
        if (!$assertion) {
            throw new \PHPUnit\Framework\AssertionFailedError('Expectation failed');
        }

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