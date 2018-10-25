<?php
declare(strict_types=1);

namespace Imposter\Server\Imposter\Matcher\Term;

use Imposter\Server\Imposter\Matcher\Term\AbstractTerm;
use Psr\Http\Message\ServerRequestInterface;

/***
 * Class Body
 * @package Imposter\Imposter\Term
 */
class Body extends AbstractTerm
{
    /**
     * @param ServerRequestInterface $request
     */
    public function match(ServerRequestInterface $request)
    {
        if (!$this->mock->getRequestBody()) {
            return;
        }

        $this->mock->getRequestBody()->evaluate($request->getBody()->getContents());
    }
}
