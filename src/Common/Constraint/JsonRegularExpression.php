<?php
declare(strict_types=1);


namespace Imposter\Common\Constraint;

use PHPUnit\Framework\Constraint\Constraint;

/**
 * Class MatchJson
 * @package Imposter\Common\Constraint
 */
class JsonRegularExpression extends Constraint
{
    /**
     * @var mixed
     */
    protected $jsonPattern;

    /**
     * MatchJson constructor.
     * @param $jsonPattern
     */
    public function __construct($jsonPattern)
    {
        parent::__construct();
        $this->jsonPattern = $jsonPattern;
    }

    /** @noinspection PhpMissingParentCallCommonInspection */
    /**
     * @param $jsonValue
     * @return bool
     */
    protected function matches($jsonValue): bool
    {
        $jsonPattern = json_decode($this->jsonPattern);
        $jsonValue = json_decode($jsonValue);

        if (!\is_object($jsonPattern) && !\is_array($jsonPattern)) {
            return false;
        }

        return $this->deepMatch($jsonPattern, $jsonValue);
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

        if (\is_array($patternObject)) {
            foreach ($patternObject as $pattern) {
                if (!$this->inArray($pattern, $valueObject)) {
                    return false;
                }
            }

            return true;
        }

        if (\is_object($valueObject)) {
            $valueObject = get_object_vars($valueObject);
            $patternObject = get_object_vars($patternObject);
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
            preg_match("/$pattern/", $value, $matches);
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
            $this->jsonPattern
        );
    }
}