<?php
declare(strict_types=1);

namespace Imposter\Di;

use Imposter\Di;

/**
 * Interface InterfaceFactory
 * @package Imposter\Di
 */
interface InterfaceFactory
{
    /**
     * @param Di $di
     * @return mixed
     */
    public function create(Di $di);
}