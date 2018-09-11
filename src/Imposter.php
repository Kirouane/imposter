<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/5/18
 * Time: 1:44 PM
 */

namespace Imposter;


use Guzzle\Http\Client;
use Imposter\Repository\Mock;



class Imposter
{
    private static $initialized = false;


    /**
     * @var int
     */
    private $port;
    private $requestPath;
    private $requestMethod;
    private $responseBody;
    private $requestBody;
    /**
     * @var int
     */
    private $times = null;
    private $id;

    /**
     * @var Di
     */
    private $di;

    public static function mock(int $port)
    {
        return new self($port);
    }

    private function __construct(int $port)
    {
        $this->di = new Di();
        $this->init();
        $this->port = $port;
    }

    public function withPath(string $requestPath): Imposter
    {
        $this->requestPath = $requestPath;
        return $this;
    }

    public function withMethod(string $requestMethod): Imposter
    {
        $this->requestMethod = $requestMethod;

        return $this;
    }


    public function withBody(string $requestBody): Imposter
    {
        $this->requestBody = $requestBody;
        return $this;
    }

    public function returnBody(string $responseBody): Imposter
    {
        $this->responseBody = $responseBody;
        return $this;
    }

    public function once(): Imposter
    {
        $this->times = '1';
        $this->timesComparison = '=';
        return $this;
    }

    public function send(): Imposter
    {
        $client = new Client();
        $request = $client->post(
            'http://localhost:8080/mock',
            null,
                 json_encode($this->getBody())
        );
        $this->id = $client->send($request)->getBody(true);
        return $this;
    }


    public function getBody()
    {
        return [
            'port' => $this->port,
            "request_uri_path" => $this->requestPath,
            "request_body" => $this->requestBody,
            "request_method" => $this->requestMethod,
            "request_headers" => '',
            "request_protocol_version" => '',
            "request_uploaded_files" => '',
            "response_body" => $this->responseBody,
            "response_headers" => '',
            'hits' => 0
        ];
    }

    public function resolve()
    {
        $client = new Client();
        $request = $client->get(
            'http://localhost:' . $this->port . '/mock',
            null
        );
        $request->getQuery()->set('id', $this->id);
        $hits = $client->send($request)->getBody(true);

        if ($this->times == null) {
            return $this;
        }

        if ($this->times !== $hits) {
            throw new \PHPUnit\Framework\AssertionFailedError('Expectation failed');
        }
        return $this;
    }

    private function init()
    {
        if (self::$initialized) {
            return;
        }

        $this->di->get(Mock::class)->recreate();
        self::$initialized = true;
    }

}