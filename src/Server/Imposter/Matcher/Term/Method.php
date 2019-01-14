<?php
declare(strict_types=1);

namespace Imposter\Server\Imposter\Matcher\Term;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Method
 * @package Imposter\Imposter\Term
 */
class Method extends AbstractTerm
{
    /**
     * @param ServerRequestInterface $request
     */
    public function match(ServerRequestInterface $request)
    {
        if (!$this->mock->getRequestMethod()) {
            return;
        }

        $this->mock->getRequestMethod()->evaluate($request->getMethod());
    }

    public function jsonSerialize()
    {
        return [
            'name' => 'method'
        ];
    }

    public function __toString()
    {
        return 'Method';
    }
}
