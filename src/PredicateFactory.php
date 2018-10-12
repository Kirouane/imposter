<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 10/11/18
 * Time: 12:32 PM
 */

namespace Imposter;

use PHPUnit\Framework\TestCase;

/**
 * Class Predicate
 * @package Imposter
 */
class PredicateFactory
{
    /**
     * @param $value
     * @return Predicate
     */
    public function equals($value): Predicate
    {
        return new Predicate('assertSame', $value);
    }

    /**
     * @param array $values
     * @return Predicate
     */
    public function inArray(array $values): Predicate
    {
        return new Predicate('assertContains', $values);
    }

    /**
     * @param string $pattern
     * @return Predicate
     */
    public function regExp(string $pattern)
    {
        return new Predicate('assertRegExp', $pattern);
    }

    /**
     * @param array $values
     * @return Predicate
     */
    public function arraySubset(array $values): Predicate
    {
        return new Predicate('assertArraySubset', $values);
    }
}