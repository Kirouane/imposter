<?php
declare(strict_types=1);

namespace Imposter\Server\Imposter\Matcher;

use GuzzleHttp\Client;
use Imposter\Common\Model\Mock;
use Imposter\Common\Model\MockProxyAlways;
use Imposter\Server\Imposter\Matcher\Term\Body;
use Imposter\Server\Imposter\Matcher\Term\Headers;
use Imposter\Server\Imposter\Matcher\Term\Method;
use Imposter\Server\Imposter\Matcher\Term\Path;
use PHPUnit\Framework\Constraint\IsIdentical;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Matcher
 * @package Imposter\Imposter
 */
class MatcherProxyAlways
{
    /**
     * @var \Imposter\Common\Model\MockProxyAlways
     */
    private $mock;

    /**
     * @var Client
     */
    private $client;

    /**
     * Matcher constructor.
     * @param \Imposter\Common\Model\MockProxyAlways $mock
     */
    public function __construct(MockProxyAlways $mock)
    {
        $this->mock = $mock;
        $this->client = new Client();
    }

    /**
     * @param ServerRequestInterface $request
     * @return \Exception[]
     */
    public function match(ServerRequestInterface $request): array
    {
        $client = new Client(['base_uri' => $this->mock->getUrl()]);

        $exceptions = [];
        $response = $client->request(
            $request->getMethod(),
            $request->getUri()->getPath(),
            [
                'headers' => $this->getHeaders($request->getHeaders()),
                'http_errors' => false,
                'query' => $request->getQueryParams()
            ]
        );

        $this->mock->setResponseBody($response->getBody()->getContents());
        $this->mock->setResponseHeaders($response->getHeaders());

        return $exceptions;
    }

    private function getHeaders(array $headers)
    {
        $newHeaders = array_map(function($value){
            return implode(' ', $value);
        }, $headers);

        $newHeaders = array_filter($newHeaders, function($key){

            return strtolower($key) !== 'host';
        }, ARRAY_FILTER_USE_KEY);

        return $newHeaders;
    }

    private function saveMock(Mock $mock)
    {
        $existentMocks = [];
        if (is_file($this->mock->getStorePath())) {
            $existentMocks = unserialize(file_get_contents($this->mock->getStorePath()));
        }

        $existentMocks[] = serialize($mock);

        file_put_contents($this->mock->getStorePath(), $existentMocks);

    }
}
