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

    /**
     * @var int
     */
    private $port = 2424;

    /**
     * @var array
     */
    private $config;

    /**
     * Config constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->port = $config['port'] ?? $this->port;
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
}