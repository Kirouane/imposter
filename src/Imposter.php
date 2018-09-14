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

    public function withPath(string $requestPath): Imposter
    {
        $this->mock->setRequestUriPath($requestPath);
        return $this;
    }

    public function withMethod(string $requestMethod): Imposter
    {
        $this->mock->setRequestMethod($requestMethod);
        return $this;
    }


    public function withBody(string $requestBody): Imposter
    {
        $this->mock->setRequestBody($requestBody);
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
        $this->timesComparison = '=';
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

        if ($this->times !== $mock->getHits()) {
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