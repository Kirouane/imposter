<?php
declare(strict_types=1);

namespace Imposter\Log;

/**
 * Class LogRepository
 * @package Imposter\Log
 */
class LogRepository
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @param $log
     */
    public function add($log)
    {
        $this->data[] = $log;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->data;
    }
}