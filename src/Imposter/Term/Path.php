<?php
declare(strict_types=1);

namespace Imposter\Imposter\Term;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Path
 * @package Imposter\Imposter\Term
 */
class Path extends AbstractTerm
{
    /**
     * @param ServerRequestInterface $request
     */
    public function match(ServerRequestInterface $request)
    {
        if (!$this->mock->getRequestUriPath()) {
            return;
        }

        $this->mock->getRequestUriPath()->evaluate($request->getUri()->getPath());
    }
}
