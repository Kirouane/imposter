<?php
declare(strict_types=1);

namespace Imposter\Server\Imposter\Term;

use Imposter\Common\Di;
use Imposter\Server\Log\LoggerFactory;
use Imposter\Server\Imposter\Term\AbstractTerm;
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
}
