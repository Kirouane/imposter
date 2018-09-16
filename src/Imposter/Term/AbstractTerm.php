<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/16/18
 * Time: 1:04 PM
 */

namespace Imposter\Imposter\Term;


use Imposter\Model\Mock;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractTerm
{
    /**
     * @var Mock
     */
    protected $mock;

    /**
     * Matcher constructor.
     * @param Mock $mock
     */
    public function __construct(Mock $mock)
    {
        $this->mock = $mock;
    }


    abstract public function match(ServerRequestInterface $request);
}