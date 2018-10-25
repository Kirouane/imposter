<?php
declare(strict_types=1);

namespace Imposter\Server\Imposter\Term;

use Imposter\Common\Model\Mock;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AbstractTerm
 * @package Imposter\Imposter\Term
 */
abstract class AbstractTerm
{
    /**
     * @var \Imposter\Common\Model\Mock
     */
    protected $mock;

    /**
     * Matcher constructor.
     * @param \Imposter\Common\Model\Mock $mock
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
