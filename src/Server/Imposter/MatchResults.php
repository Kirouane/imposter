<?php
declare(strict_types=1);

namespace Imposter\Server\Imposter;


/**
 * Class MatchResults
 * @package Imposter\Imposter
 */
class MatchResults extends \ArrayObject
{
    /**
     * @return string
     */
    public function __toString()
    {
        return (string)array_reduce($this->getArrayCopy(), function ($string, $item) {
            return $string . ' ' . $item;
        });
    }
}