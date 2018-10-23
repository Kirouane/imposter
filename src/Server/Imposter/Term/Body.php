<?php
declare(strict_types=1);

namespace Imposter\Server\Imposter\Term;

use Imposter\Server\Imposter\Term\AbstractTerm;
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
