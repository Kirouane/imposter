<?php
declare(strict_types=1);

namespace Imposter\Repository;

use Imposter\Model\Mock;
use Imposter\Server;

/**
 * Class HttpMock
 * @package Imposter\Repository
 */
class HttpMock
{
    /**
     * @var \\GuzzleHttp\ClientInterface
     */
    private $client;

    /**
     * HttpMock constructor.
     * @param \GuzzleHttp\ClientInterface|null $client
     */
    public function __construct(\GuzzleHttp\ClientInterface $client = null)
    {
        $this->client = $client ?: new \GuzzleHttp\Client();
    }

    /**
     * @param Mock $mock
     * @return Mock
     * @throws \Exception
     */
    public function insert(Mock $mock): Mock
    {
        $response = $this->client->post(Server::URL . '/mock', ['body' => serialize($mock)]);

        if (!$response) {
            throw new \UnexpectedValueException('Response not found');
        }

        return unserialize($response->getBody()->getContents(), [Mock::class]);
    }

    /**
     * @param Mock $mock
     * @return Mock
     * @throws \Exception
     */
    public function find(Mock $mock): Mock
    {
        $response = $this->client->get(Server::URL . '/mock', ['query' => ['id' => $mock->getId()]]);

        if (!$response) {
            throw new \UnexpectedValueException('Response body not found');
        }

        return unserialize($response->getBody()->getContents(), [Mock::class]);
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

        return unserialize($response->getBody()->getContents(), [Mock::class]);
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
    public function start()
    {
        $dir = \dirname(__DIR__ , 2) . '/bin';
        pclose(popen("php $dir/Imposter.php start &", 'r'));
        $sleep = 1/100;
        $count = 0;
        while (!$this->isStarted()) {
            if ($count > 1/$sleep) {
                throw new \Exception('Cannot start Imposter');
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
