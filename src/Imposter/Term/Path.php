<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/16/18
 * Time: 1:03 PM
 */

namespace Imposter\Imposter\Term;


use Psr\Http\Message\ServerRequestInterface;

class Path extends AbstractTerm
{
    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function match(ServerRequestInterface $request)
    {
        if (!$this->mock->getRequestUriPath()) {
            return true;
        }

        try {
            $this->mock->getRequestUriPath()->evaluate($request->getUri()->getPath());
        } catch (\PHPUnit\Framework\AssertionFailedError $e) {
            return false;
        }

        return true;
    }
}