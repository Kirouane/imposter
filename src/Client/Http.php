<?php
declare(strict_types=1);

namespace Imposter\Client;

use Imposter\Common\Model\Mock;
use Imposter\Common\Model\MockAbstract;
use Imposter\Server\Server;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Http
 * @package Imposter\Client
 */
class Http
{
    const DEFAULT_TIMEOUT = 10; // seconds
    /**
     * @var \\GuzzleHttp\Client
     */
    private $client;

    /**
     * @var Console
     */
    private $console;

    /**
     * @var int
     */
    private $timeout = self::DEFAULT_TIMEOUT;

    /**
     * HttpMock constructor.
     * @param \GuzzleHttp\Client $client
     * @param Console $console
     */
    public function __construct(\GuzzleHttp\Client $client, Console $console)
    {
        $this->client = $client;
        $this->console = $console;
    }

    /**
     * @param \Imposter\Common\Model\MockAbstract $mock
     * @return \Imposter\Common\Model\MockAbstract
     * @throws \Exception
     */
    public function insert(MockAbstract $mock): MockAbstract
    {
        $response = $this->client->post(
            Server::URL . '/mock',
            ['body' => serialize($mock)]
        );

        if (!$response) {
            throw new \UnexpectedValueException('Response not found');
        }

        return $this->getMockFromHttpResponse($response);
    }

    /**
     * @param ResponseInterface $response
     * @return mixed
     */
    private function getMockFromHttpResponse(ResponseInterface $response)
    {
        $content = $response->getBody()->getContents();
        $acceptedHttpCodes = [200, 201];

        if (!\in_array($response->getStatusCode(), $acceptedHttpCodes, true)) {
            throw new \RuntimeException($content);
        }

        $mock = @unserialize($content, [Mock::class]);
        if (!$mock instanceof MockAbstract) {
            throw new \UnexpectedValueException('Cannot unserialize this content : "' . $content . '"');
        }

        return $mock;
    }

    /**
     * @param \Imposter\Common\Model\MockAbstract $mock
     * @return \Imposter\Common\Model\MockAbstract
     * @throws \Exception
     */
    public function find(MockAbstract $mock): MockAbstract
    {
        $response = $this->client->get(Server::URL . '/mock', ['query' => ['id' => $mock->getId(), 'port' => $mock->getPort()]]);

        if (!$response) {
            throw new \UnexpectedValueException('Response body not found');
        }

        return $this->getMockFromHttpResponse($response);
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        $response = $this->client->get(Server::URL . '/mock');

        if (!$response) {
            throw new \UnexpectedValueException('Response body not found');
        }

        $content = $response->getBody()->getContents();
        $acceptedHttpCodes = [200, 201];

        if (!\in_array($response->getStatusCode(), $acceptedHttpCodes, true)) {
            throw new \RuntimeException($content);
        }

        $mocks = @unserialize($content, [Mock::class]);
        if (!\is_array($mocks)) {
            throw new \UnexpectedValueException('Cannot unserialize this content : "' . $content . '"');
        }

        return $mocks;
    }

    /**
     * @throws \Exception
     */
    public function drop()
    {
        $this->client->delete(Server::URL . '/mock', null);
    }

    public function start()
    {
        $this->console->startImposter();

        $times = 100;
        $timeout = $this->timeout;
        $sleep = $timeout / $times;

        foreach (range(1, $times) as $i) {
            $e = null;
            try {
                $this->findAll();
            } catch (\Exception $e) {

            }

            \usleep((int)($sleep * 1000000));

            if (!$e) {
                return;
            }
        }

        throw $e;
    }

    /**
     * @return bool
     */
    public function stop()
    {
        try {
            $this->client->delete(Server::URL . '/mock/server', null);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function restart(): bool
    {
        $this->stop();
        $this->start();
        return true;
    }

    /**
     * @return bool
     */
    public function isStarted(): bool
    {
        try {
            $this->findAll();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param int $timeout
     * @return Http
     */
    public function setTimeout(int $timeout): Http
    {
        $this->timeout = $timeout;
        return $this;
    }
}
