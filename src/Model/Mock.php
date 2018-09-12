<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/12/18
 * Time: 6:45 PM
 */

namespace Imposter\Model;


class Mock implements \JsonSerializable
{
    private $id;
    private $port;
    private $requestUriPath;
    private $requestBody;
    private $requestMethod;
    private $response_body;

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
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param mixed $port
     * @return Mock
     */
    public function setPort($port)
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequestUriPath()
    {
        return $this->requestUriPath;
    }

    /**
     * @param mixed $requestUriPath
     * @return Mock
     */
    public function setRequestUriPath($requestUriPath)
    {
        $this->requestUriPath = $requestUriPath;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequestBody()
    {
        return $this->requestBody;
    }

    /**
     * @param mixed $requestBody
     * @return Mock
     */
    public function setRequestBody($requestBody)
    {
        $this->requestBody = $requestBody;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * @param mixed $requestMethod
     * @return Mock
     */
    public function setRequestMethod($requestMethod)
    {
        $this->requestMethod = $requestMethod;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponseBody()
    {
        return $this->response_body;
    }

    /**
     * @param mixed $response_body
     * @return Mock
     */
    public function setResponseBody($response_body)
    {
        $this->response_body = $response_body;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHits()
    {
        return $this->hits;
    }

    /**
     * @param mixed $hits
     * @return Mock
     */
    public function setHits($hits)
    {
        $this->hits = $hits;
        return $this;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {

    }

    public function toArray()
    {

    }
}