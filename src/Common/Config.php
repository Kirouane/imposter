<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 12/30/18
 * Time: 11:47 AM
 */

namespace Imposter\Common;


class Config
{
    const HOST     = '127.0.0.1';

    const PROTOCOL = 'http';

    const DEFAULT_PORT = 2424;

    const DEFAULT_TIMEOUT = 10; // seconds;

    /**
     * @var int
     */
    private $port = self::DEFAULT_PORT;

    /**
     * @var array
     */
    private $config;

    /**
     * Config constructor.
     * @param array $config
     * @param int $port
     */
    public function __construct(array $config, int $port)
    {
        $this->config = $config;
        $this->port = $port;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return self::PROTOCOL . '://' . self::HOST . ':' . $this->port;
    }

    /**
     * @return int
     */
    public function getServerTimeout(): int
    {
        return $this->config['server']['timeout'] ?? self::DEFAULT_TIMEOUT;
    }

    public function isFileLoggerEnabled(): bool
    {
        return $this->getLogFilePath() !== null;
    }

    public function getLogFilePath()
    {
        return $this->config['log']['filePath'] ?? null;
    }
}