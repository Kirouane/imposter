<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 10/11/18
 * Time: 12:35 PM
 */

namespace Imposter;

use PHPUnit\Framework\TestCase;

/**
 * Class Predicate
 * @package Imposter
 */
class Predicate
{
    /**
     * @var string
     */
    private $name;

    private $value;

    private $context;

    /**
     * Predicate constructor.
     * @param string $name
     * @param $value
     * @param $context
     */
    public function __construct(string $name, $value, $context = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->context = $context;
    }

    /**
     * @param $compareValue
     */
    public function evaluate($compareValue)
    {
        TestCase::{$this->name}($this->value, $compareValue);
    }

    public function toString()
    {
        return (string)$this;
    }

    public function __toString()
    {
        return $this->name . ' : ' . $this->value;
    }
}