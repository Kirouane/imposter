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
    /**
     * @var \\GuzzleHttp\Client
     */
    private $client;
    /**
     * @var Console
     */
    private $console;

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

    /**
     * @return bool
     * @throws \Exception
     */
    public function start(): bool
    {
        $this->console->startImposter();

        $sleep = 1 / 100;
        $count = 0;
        while (!$this->isStarted()) {
            if ($count > 1 / $sleep) {
                throw new \RuntimeException('Cannot start Imposter');
            }
            usleep((int)($sleep * 1000));
            $count ++;
        }

        return true;
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
}
