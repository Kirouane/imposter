<?php
declare(strict_types=1);

namespace Imposter\Common\Di;

use Imposter\Common\Di;

/**
 * Interface InterfaceFactory
 * @package Imposter\Common\Di
 */
interface InterfaceFactory
{
    /**
     * @param \Imposter\Common\Di $di
     * @return mixed
     */
    public function create(Di $di);
}