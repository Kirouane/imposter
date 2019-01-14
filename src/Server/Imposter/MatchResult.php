<?php
declare(strict_types=1);

namespace Imposter\Server\Imposter;

use Imposter\Common\Model\MockAbstract;
use Imposter\Server\Imposter\Matcher\TermResult;

/**
 * Class MatchResult
 * @package Imposter\Server\Imposter
 */
class MatchResult implements \JsonSerializable
{
    /**
     * @var MockAbstract
     */
    private $mock;

    /**
     * @var \Exception[]
     */
    private $termResults;

    /**
     * MatchResult constructor.
     * @param MockAbstract $mock
     * @param TermResult[] $termResults
     */
    public function __construct(MockAbstract $mock, array $termResults)
    {
        $this->mock = $mock;
        $this->termResults = $termResults;
    }

    /**
     * @return MockAbstract
     */
    public function getMock(): MockAbstract
    {
        return $this->mock;
    }

    /**
     * @return TermResult[]
     */
    public function getTermResults(): array
    {
        return $this->termResults;
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
        $mock = $this->getMock();


        return [
            'host' => 'localhost:' . $mock->getPort(),
            'file' => $mock->getFile(),
            'line' => $mock->getLine(),
            'results' => $this->getTermResults()
        ];
    }
}