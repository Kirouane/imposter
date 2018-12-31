<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 11/18/18
 * Time: 3:41 PM
 */

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
        pclose(popen("$command &", 'r'));
    }
}