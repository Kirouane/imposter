<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 1/11/19
 * Time: 6:30 PM
 */

namespace Imposter\Server\Imposter\Matcher;


use Imposter\Server\Imposter\Matcher\Term\AbstractTerm;

class TermResult implements \JsonSerializable
{
    /**
     * @var AbstractTerm
     */
    private $term;
    /**
     * @var \Exception
     */
    private $exception;

    public function __construct(AbstractTerm $term, \Exception $exception)
    {
        $this->term = $term;
        $this->exception = $exception;
    }

    public function jsonSerialize()
    {
        return [
            'term' => $this->term,
            'errors' => explode("\n", \PHPUnit\Framework\TestFailure::exceptionToString($this->exception))
        ];
    }

    /**
     * @return AbstractTerm
     */
    public function getTerm(): AbstractTerm
    {
        return $this->term;
    }

    /**
     * @return \Exception
     */
    public function getException(): \Exception
    {
        return $this->exception;
    }
}