<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 10/25/18
 * Time: 6:41 PM
 */

namespace Imposter\Common\Model;

/**
 * Class MockAbstract
 * @package Imposter\Common\Model
 */
abstract class MockAbstract
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
     * MockAbstract constructor.
     * @param int $port
     */
    public function __construct(int $port)
    {
        $this->port = $port;
    }


    /**
     * @return null|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null|string $id
     * @return MockAbstract
     */
    public function setId(string $id): MockAbstract
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
     * @return MockAbstract
     */
    public function setPort(int $port): MockAbstract
    {
        $this->port = $port;
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
     * @return MockAbstract
     */
    public function setFile(string $file): MockAbstract
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
     * @return MockAbstract
     */
    public function setLine(int $line): MockAbstract
    {
        $this->line = $line;
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
     * @return MockAbstract
     */
    public function setHits(int $hits): MockAbstract
    {
        $this->hits = $hits;
        return $this;
    }

    /**
     * @return MockAbstract
     */
    public function hit() : MockAbstract
    {
        $this->hits++;
        return $this;
    }

    /***
     * @return string
     */
    abstract public function toString(): string;
}