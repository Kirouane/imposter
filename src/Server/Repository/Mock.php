<?php
declare(strict_types=1);

namespace Imposter\Server\Repository;

use Imposter\Common\Model\MockAbstract;
use Imposter\Server\Imposter\Matcher\Matcher;
use Imposter\Server\Imposter\MatchResult;
use Imposter\Server\Imposter\MatchResults;
use Monolog\Logger;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Mock
 * @package Imposter\Repository
 */
class Mock
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @var Logger
     */
    private $logger;

    /**
     * Mock constructor.
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }


    public function recreate()
    {
        $this->data = [];
    }

    /**
     * @return bool
     */
    public function hasData(): bool
    {
        return !empty($this->data);
    }

    /**
     * @param MockAbstract $mock
     * @return MockAbstract
     */
    public function insert(MockAbstract $mock): MockAbstract
    {
        $mock->setId(uniqid('', true));
        $this->data[$mock->getPort()][$mock->getId()] = $mock;
        return $mock;
    }

    /**
     * @param $id
     * @return MockAbstract|null
     */
    public function findById(int $port, $id)
    {
        return $this->data[$port][$id] ?? null;
    }

    /**
     * @param MockAbstract $mock
     * @return MockAbstract
     */
    public function update(MockAbstract $mock): MockAbstract
    {
        $this->data[$mock->getPort()][$mock->getId()] = $mock;
        return $mock;
    }

    public function drop()
    {
        $this->recreate();
    }

    /**
     * @param ServerRequestInterface $request
     * @return MockAbstract|null
     */
    public function matchRequest(ServerRequestInterface $request)
    {
        $match = null;

        $results = new MatchResults();
        /** @var MockAbstract $mock */
        foreach ($this->data[$request->getUri()->getPort()] as $mock) {
            $matcher    = new Matcher($mock);
            $exceptions = $matcher->match($request);
            $results[] = new MatchResult($mock, $exceptions);
            if (empty($exceptions)) {
                $this->logger->info('Mock found');
                $match = $mock;
                break;
            }
        }

        if (!$match) {
            $this->logger->warning('Mock not match', ['matchResult' => $results]);
        }

        return $match;
    }

    /**
     * @return MockAbstract[]
     */
    public function findAll(): array
    {
        return $this->data;
    }
}
