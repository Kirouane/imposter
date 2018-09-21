<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/21/18
 * Time: 12:46 PM
 */

namespace Imposter\Log;


class LogRepository
{
    private $data = [];

    public function add($log)
    {
        $this->data[] = $log;
    }

    public function getAll()
    {
        return $this->data;
    }
}