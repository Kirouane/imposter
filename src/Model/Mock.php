<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/12/18
 * Time: 6:45 PM
 */

namespace Imposter\Model;

use PHPUnit\Framework\Constraint\Constraint;

class Mock
{
    /**
     * @var string|null
     */
    private $id;

    /**
     * @var int
     */
    private $port;

    /**
     * @var Constraint|null
     */
    private $requestUriPath;

    /**
     * @var Constraint|null
     */
    private $requestBody;

    /**
     * @var Constraint|null
     */
    private $requestMethod;

    /**
     * @var string|null
     */
    private $responseBody;

    /**
     * @var int
     */
    private $hits = 0;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Mock
     */
    public function setId($id): Mock
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param int $port
     * @return Mock
     */
    public function setPort(int $port): Mock
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return Constraint|null
     */
    public function getRequestUriPath()
    {
        return $this->requestUriPath;
    }

    /**
     * @param Constraint $requestUriPath
     * @return Mock
     */
    public function setRequestUriPath(Constraint $requestUriPath): Mock
    {
        $this->requestUriPath = $requestUriPath;
        return $this;
    }

    /**
     * @return Constraint|null
     */
    public function getRequestBody()
    {
        return $this->requestBody;
    }

    /**
     * @param Constraint $requestBody
     * @return Mock
     */
    public function setRequestBody(Constraint $requestBody): Mock
    {
        $this->requestBody = $requestBody;
        return $this;
    }

    /**
     * @return Constraint|null
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * @param Constraint $requestMethod
     * @return Mock
     */
    public function setRequestMethod(Constraint $requestMethod): Mock
    {
        $this->requestMethod = $requestMethod;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getResponseBody()
    {
        return $this->responseBody;
    }

    /**
     * @param string $responseBody
     * @return Mock
     */
    public function setResponseBody(string $responseBody): Mock
    {
        $this->responseBody = $responseBody;
        return $this;
    }

    /**
     * @return int
     */
    public function getHits(): int
    {
        return $this->hits;
    }

    /**
     * @param int $hits
     * @return Mock
     */
    public function setHits(int $hits): Mock
    {
        $this->hits = $hits;
        return $this;
    }
}
