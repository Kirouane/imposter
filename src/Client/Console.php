<?php
declare(strict_types=1);

namespace Imposter\Client;

/**
 * Class Console
 * @package Imposter\Client
 */
class Console
{
    /**
     * @var string
     */
    private $configPath;
    /**
     * @var int
     */
    private $port;

    public function __construct(int $port, string $configPath = null)
    {
        $this->configPath = $configPath;
        $this->port = $port;
    }

    public function startImposter()
    {
        $root = \dirname(__DIR__ , 2);
        $binDir = $root . '/bin';
        $command = "php $binDir/Imposter.php start $this->port";
        if ($this->configPath) {
            $command .= " -c $this->configPath";
        }
        $handler = popen("$command &", 'r');

        // Since php 7.4, the speed of php is so fast that there is a race condition without this waiting time
        \usleep(1000000);
        pclose($handler);
    }
}