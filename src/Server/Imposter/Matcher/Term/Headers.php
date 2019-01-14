<?php
declare(strict_types=1);

namespace Imposter\Server\Imposter\Matcher\Term;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Header
 * @package Imposter\Imposter\Term
 */
class Headers extends AbstractTerm
{
    /**
     * @param ServerRequestInterface $request
     */
    public function match(ServerRequestInterface $request)
    {
        if (!$this->mock->getRequestHeaders()) {
            return;
        }

        $this->mock->getRequestHeaders()->evaluate($request->getHeaders());
    }

    public function jsonSerialize()
    {
        return [
            'name' => 'headers'
        ];
    }

    public function __toString()
    {
        return 'Headers';
    }
}
