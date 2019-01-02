<?php
declare(strict_types=1);


namespace Imposter\Common\Constraint;

/**
 * Class MatchJson
 * @package Imposter\Common\Constraint
 */
class JsonRegularExpression extends ArrayRegularExpression
{
    /**
     * @var mixed
     */
    protected $jsonPattern;

    /**
     * MatchJson constructor.
     * @param $arrayPattern
     */
    public function __construct($jsonPattern)
    {
        $this->jsonPattern = $jsonPattern;
        parent::__construct(json_decode($jsonPattern, true));
    }

    /** @noinspection PhpMissingParentCallCommonInspection */
    /**
     * @param $arrayValue
     * @return bool
     */
    protected function matches($arrayValue): bool
    {
        $arrayValue = json_decode($arrayValue, true);
        return parent::matches($arrayValue);
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