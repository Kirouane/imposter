<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/16/18
 * Time: 1:04 PM
 */

namespace Imposter\Imposter\Term;


use Psr\Http\Message\ServerRequestInterface;

class Method extends AbstractTerm
{
    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function match(ServerRequestInterface $request)
    {
        if (!$this->mock->getRequestMethod()) {
            return true;
        }

        $this->mock->getRequestMethod()->evaluate($request->getMethod());
    }
}