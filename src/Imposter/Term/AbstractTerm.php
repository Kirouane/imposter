<?php
declare(strict_types=1);

namespace Imposter\Imposter\Term;

use Imposter\Model\Mock;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AbstractTerm
 * @package Imposter\Imposter\Term
 */
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

    /**
     * @param ServerRequestInterface $request
     * @return void
     */
    abstract public function match(ServerRequestInterface $request);
}
