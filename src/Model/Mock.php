<?php
declare(strict_types=1);

namespace Imposter\Model;

use Imposter\Predicate;

/**
 * Class Mock
 * @package Imposter\Model
 */
class Mock implements \JsonSerializable
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
     * @var Predicate|null
     */
    private $requestUriPath;

    /**
     * @var Predicate|null
     */
    private $requestBody;

    /**
     * @var Predicate|null
     */
    private $requestMethod;

    /**
     * @var Predicate|null
     */
    private $requestHeaders;

    /**
     * @var string|null
     */
    private $responseBody;

    /**
     * @var array
     */
    private $responseHeaders = [];

    /**
     * @var string
     */
    private $file;

    /**
     * @var int
     */
    private $line;

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
     * @return Predicate|null
     */
    public function getRequestUriPath()
    {
        return $this->requestUriPath;
    }

    /**
     * @param Predicate $requestUriPath
     * @return Mock
     */
    public function setRequestUriPath(Predicate $requestUriPath): Mock
    {
        $this->requestUriPath = $requestUriPath;
        return $this;
    }

    /**
     * @return Predicate|null
     */
    public function getRequestBody()
    {
        return $this->requestBody;
    }

    /**
     * @param Predicate $requestBody
     * @return Mock
     */
    public function setRequestBody(Predicate $requestBody): Mock
    {
        $this->requestBody = $requestBody;
        return $this;
    }

    /**
     * @return Predicate|null
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * @param Predicate $requestMethod
     * @return Mock
     */
    public function setRequestMethod(Predicate $requestMethod): Mock
    {
        $this->requestMethod = $requestMethod;
        return $this;
    }

    /**
     * @return null|Predicate
     */
    public function getRequestHeaders()
    {
        return $this->requestHeaders;
    }

    /**
     * @param null|Predicate $requestHeaders
     * @return Mock
     */
    public function setRequestHeaders(Predicate $requestHeaders): Mock
    {
        $this->requestHeaders = $requestHeaders;
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
     * @return array
     */
    public function getResponseHeaders(): array
    {
        return $this->responseHeaders;
    }

    /**
     * @param array $responseHeaders
     * @return Mock
     */
    public function setResponseHeaders(array $responseHeaders): Mock
    {
        $this->responseHeaders = $responseHeaders;
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

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @param string $file
     * @return Mock
     */
    public function setFile(string $file): Mock
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * @param int $line
     * @return Mock
     */
    public function setLine(int $line): Mock
    {
        $this->line = $line;
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
            'request' => [
                'path' => $this->getRequestUriPath() ? $this->getRequestUriPath()->toString() : null,
                'method' => $this->getRequestMethod() ? $this->getRequestMethod()->toString() : null,
                'body' => $this->getRequestBody() ? $this->getRequestBody()->toString() : null,
                'headers' => $this->getRequestHeaders() ? $this->getRequestHeaders()->toString() : null
            ],
            'response' => [
                'body' => $this->getResponseBody(),
                'headers' => $this->getResponseHeaders(),
            ]
        ];
    }
}
