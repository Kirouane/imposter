<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 10/18/18
 * Time: 12:05 PM
 */

namespace Imposter\Predicate;

use PHPUnit\Framework\Constraint\Constraint;

/**
 * Class MatchJson
 * @package Imposter\Predicate
 */
class MatchJson extends Constraint
{

    /**
     * @var mixed
     */
    protected $jsonValue;

    /**
     * @param mixed $value
     */
    public function __construct($jsonValue)
    {
        parent::__construct();
        $this->jsonValue = $jsonValue;
    }

    /**
     * @param mixed $other
     * @return bool
     */
    protected function matches($jsonOther)
    {
        $valueObject = json_decode($this->jsonValue);
        $otherObject = json_decode($jsonOther);

        if (!\is_object($valueObject) && !\is_array($valueObject)) {
            return false;
        }

        return $this->deepMatch($valueObject, $otherObject);


    }

    private function deepMatch($valueObject, $otherObject)
    {
        if (\gettype($valueObject) !== \gettype($otherObject)) {
            return false;
        }

        if (is_array($otherObject)) {
            foreach ($otherObject as $otherValue) {
                if (!$this->inArray($otherValue, $valueObject)) {
                    return false;
                }
            }

            return true;
        }

        if (\is_object($otherObject)) {
            $otherObject = get_object_vars($otherObject);
            $valueObject = get_object_vars($valueObject);
            foreach ($otherObject as $key => $value) {
                if (!isset($valueObject[$key]) || !$this->compare($valueObject[$key], $value)) {
                    return false;
                }
            }

            return true;
        }

        return $this->compare($valueObject, $otherObject);
    }

    private function inArray($otherValue, $valueArray)
    {
        foreach ($valueArray as $value) {
            if ($this->compare($value, $otherValue)) {
                return true;
            }
        }

        return false;
    }

    private function compare($value, $other)
    {
        if (!\is_object($value) && !\is_array($value)) {
            preg_match("/$other/", $value, $matches);
            return count($matches) > 0;
        }

        return $this->deepMatch($value, $other);
    }


    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        // TODO: Implement toString() method.
    }
}