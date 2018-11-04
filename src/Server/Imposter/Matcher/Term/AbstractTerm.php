<?php
declare(strict_types=1);

namespace Imposter\Server\Imposter\Matcher\Term;

use Imposter\Common\Model\MockAbstract;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AbstractTerm
 * @package Imposter\Imposter\Term
 */
abstract class AbstractTerm
{
    /**
     * @var \Imposter\Common\Model\MockAbstract
     */
    protected $mock;

    /**
     * Matcher constructor.
     * @param \Imposter\Common\Model\MockAbstract $mock
     */
    public function __construct(MockAbstract $mock)
    {
        $this->mock = $mock;
    }

    /**
     * @param ServerRequestInterface $request
     * @return void
     */
    abstract public function match(ServerRequestInterface $request);
}
