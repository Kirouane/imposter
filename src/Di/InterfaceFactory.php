<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/21/18
 * Time: 12:51 PM
 */

namespace Imposter\Di;


use Imposter\Di;

interface InterfaceFactory
{
    public function create(Di $di);
}