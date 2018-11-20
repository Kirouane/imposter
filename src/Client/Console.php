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
        $dir = \dirname(__DIR__ , 2) . '/bin';
        pclose(popen("php $dir/Imposter.php start &", 'r'));
    }
}