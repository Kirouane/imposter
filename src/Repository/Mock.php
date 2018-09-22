<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/10/18
 * Time: 12:47 PM
 */

namespace Imposter\Repository;

use Imposter\Imposter\Matcher;
use Imposter\Model\Mock as MockModel;
use Monolog\Logger;
use PHPUnit\Framework\TestFailure;
use Psr\Http\Message\ServerRequestInterface;

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

        $exceptions = [];
        /** @var MockModel $mock */
        foreach ($this->data as $mock) {
            $matcher    = new Matcher($mock);
            $exceptions = array_merge($exceptions, $matcher->match($request));

            if (empty($exceptions)) {
                $this->logger->info('Mock found');
                $match = $mock;
                break;
            }
        }

        if (!$match) {
            foreach ($exceptions as $exception) {
                $this->logger->warning(TestFailure::exceptionToString($exception));
            }

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
