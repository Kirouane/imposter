<?php
declare(strict_types=1);

namespace Imposter\Common\Model;

/**
 * Class Mock
 * @package Imposter\Model
 */
class MockProxyAlways extends MockAbstract implements \JsonSerializable
{

    /**
     * @var string
     */
    private $storePath;

    /**
     * @var string
     */
    private $url;

    /**
     * @var bool|null
     */
    private $requestMethod = false;

    /**
     * @var string|null
     */
    private $responseBody;

    /**
     * @var array
     */
    private $responseHeaders = [];


    /**
     * @return string
     */
    public function getStorePath(): string
    {
        return $this->storePath;
    }

    /**
     * @param string $storePath
     * @return MockProxyAlways
     */
    public function setStorePath(string $storePath): MockProxyAlways
    {
        $this->storePath = $storePath;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return MockProxyAlways
     */
    public function setUrl(string $url): MockProxyAlways
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return null|bool
     */
    public function getRequestMethod(): bool
    {
        return $this->requestMethod;
    }

    /**
     * @param bool $requestMethod
     * @return MockProxyAlways
     */
    public function setRequestMethod(bool $requestMethod): MockProxyAlways
    {
        $this->requestMethod = $requestMethod;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getResponseBody(): string
    {
        return $this->responseBody;
    }

    /**
     * @param null|string $responseBody
     * @return MockProxyAlways
     */
    public function setResponseBody(string $responseBody): MockProxyAlways
    {
        $this->responseBody = $responseBody;
        return $this;
    }

    /**
     * @return array
     */
    public function getResponseHeaders(): array
    {
        return $this->responseHeaders;
    }

    /**
     * @param array $responseHeaders
     * @return MockProxyAlways
     */
    public function setResponseHeaders(array $responseHeaders): MockProxyAlways
    {
        $this->responseHeaders = $responseHeaders;
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
        return [
            'id' => $this->getId(),
            'url' => $this->getUrl(),
            'storePath' => $this->getStorePath()
        ];
    }

    /**
     * @return int
     */
    public function toString(): string
    {
        return (string)printf(
            "- Url %s \n" .
            "- StorePath %s \n",
            $this->getUrl(),
            $this->getStorePath()
        );
    }
}
