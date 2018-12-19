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
    public function startImposter()
    {
        $root = \dirname(__DIR__ , 2);
        $binDir = $root . '/bin';
        pclose(popen("php $binDir/Imposter.php start &", 'r'));
    }
}