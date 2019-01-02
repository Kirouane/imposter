<?php
declare(strict_types=1);

namespace Imposter\Server\Imposter\Matcher\Term;

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

        $parserBody = $request->getParsedBody();
        if (!empty($parserBody)) {
            $this->mock->getRequestBody()->evaluate($parserBody);
        } else {
            $body = clone $request->getBody();
            $this->mock->getRequestBody()->evaluate($body->getContents());
        }
    }
}
