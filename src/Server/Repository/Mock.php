<?php
declare(strict_types=1);

namespace Imposter\Server\Repository;

use Imposter\Server\Imposter\Matcher;
use Imposter\Server\Imposter\MatchResult;
use Imposter\Server\Imposter\MatchResults;
use Imposter\Common\Model\Mock as MockModel;
use Monolog\Logger;
use PHPUnit\Framework\TestFailure;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Mock
 * @package Imposter\Repository
 */
class Mock
{
    /**
     * @var MockModel[]
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
     * @param MockModel $mock
     * @return MockModel
     */
    public function insert(MockModel $mock): MockModel
    {
        $mock->setId(uniqid('', true));
        $this->data[$mock->getId()] = $mock;
        return $mock;
    }

    /**
     * @param $id
     * @return MockModel|null
     */
    public function findById($id)
    {
        return $this->data[$id] ?? null;
    }

    /**
     * @param MockModel $row
     * @return MockModel
     */
    public function update(MockModel $row): MockModel
    {
        $this->data[$row->getId()] = $row;
        return $row;
    }

    public function drop()
    {
        $this->recreate();
    }

    /**
     * @param ServerRequestInterface $request
     * @return MockModel|null
     */
    public function matchRequest(ServerRequestInterface $request)
    {
        $match = null;

        $results = new MatchResults();
        /** @var MockModel $mock */
        foreach ($this->data as $mock) {
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
     * @return MockModel[]
     */
    public function findAll(): array
    {
        return $this->data;
    }
}
