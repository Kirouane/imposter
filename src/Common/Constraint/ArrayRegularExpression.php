<?php
declare(strict_types=1);


namespace Imposter\Common\Constraint;

use PHPUnit\Framework\Constraint\Constraint;

/**
 * Class ArrayRegularExpression
 * @package Imposter\Common\Constraint
 */
class ArrayRegularExpression extends Constraint
{
    /**
     * @var mixed
     */
    protected $arrayPattern;

    /**
     * MatchJson constructor.
     * @param $arrayPattern
     */
    public function __construct(array $arrayPattern)
    {
        parent::__construct();
        $this->arrayPattern = $arrayPattern;
    }

    /** @noinspection PhpMissingParentCallCommonInspection */
    /**
     * @param $arrayValue
     * @return bool
     */
    protected function matches($arrayValue): bool
    {
        if (!\is_array($arrayValue)) {
            return false;
        }

        return $this->deepMatch($this->arrayPattern, $arrayValue);
    }

    /**
     * @param $patternObject
     * @param $valueObject
     * @return bool
     */
    private function deepMatch($patternObject, $valueObject): bool
    {
        if (\gettype($patternObject) !== \gettype($valueObject)) {
            return false;
        }

        if (!$this->isAssociativeArray($patternObject)) {
            foreach ($patternObject as $pattern) {
                if (!$this->inArray($pattern, $valueObject)) {
                    return false;
                }
            }

            return true;
        }

        if ($this->isAssociativeArray($valueObject)) {
            foreach ($patternObject as $key => $pattern) {
                if (!isset($valueObject[$key]) || !$this->compare($pattern, $valueObject[$key])) {
                    return false;
                }
            }

            return true;
        }

        return $this->compare($patternObject, $valueObject);
    }

    /**
     * @param array $array
     * @return bool
     */
    private function isAssociativeArray(array $array): bool
    {
        if ([] === $array) {
            return false;
        }
        return array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * @param $pattern
     * @param $valueObject
     * @return bool
     */
    private function inArray($pattern, $valueObject): bool
    {
        /** @var \Traversable $valueObject */
        foreach ($valueObject as $value) {
            if ($this->compare($value, $pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $pattern
     * @param $value
     * @return bool
     */
    private function compare($pattern, $value): bool
    {
        if (!\is_object($pattern) && !\is_array($pattern)) {
            preg_match("/$pattern/", (string)$value, $matches);
            return \count($matches) > 0;
        }

        return $this->deepMatch($pattern, $value);
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString(): string
    {
        return \sprintf(
            'matches JSON string "%s"',
            $this->arrayPattern
        );
    }
}