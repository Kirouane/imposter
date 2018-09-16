<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/16/18
 * Time: 12:27 PM
 */

namespace Imposter\Imposter;


use Imposter\Imposter\Term\AbstractTerm;
use Imposter\Imposter\Term\Body;
use Imposter\Imposter\Term\Method;
use Imposter\Imposter\Term\Path;
use Imposter\Model\Mock;
use Psr\Http\Message\ServerRequestInterface;

class Matcher
{
    /**
     * @var Mock
     */
    private $mock;

    /**
     * Matcher constructor.
     * @param Mock $mock
     */
    public function __construct(Mock $mock)
    {
        $this->mock = $mock;
    }


    public function match(ServerRequestInterface $request)
    {
        /** @var AbstractTerm[ $terms */
        $terms = [
            new Body($this->mock),
            new Path($this->mock),
            new Method($this->mock),
        ];

        $matched = true;

        /** @var AbstractTerm $term */
        foreach ($terms as $term) {
            $matched &= $term->match($request);
        }

        return $matched;
    }
}