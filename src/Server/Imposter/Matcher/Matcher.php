<?php
declare(strict_types=1);

namespace Imposter\Server\Imposter\Matcher;

use Imposter\Common\Model\MockAbstract;
use Imposter\Server\Imposter\Matcher\Term\Body;
use Imposter\Server\Imposter\Matcher\Term\Headers;
use Imposter\Server\Imposter\Matcher\Term\Method;
use Imposter\Server\Imposter\Matcher\Term\Path;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Matcher
 * @package Imposter\Imposter
 */
class Matcher
{
    /**
     * @var \Imposter\Common\Model\MockAbstract
     */
    private $mock;

    /**
     * Matcher constructor.
     * @param \Imposter\Common\Model\MockAbstract $mock
     */
    public function __construct(MockAbstract $mock)
    {
        $this->mock = $mock;
    }

    /**
     * @param ServerRequestInterface $request
     * @return TermResult[]
     */
    public function match(ServerRequestInterface $request): array
    {
        $terms = [
            new Body($this->mock),
            new Path($this->mock),
            new Method($this->mock),
            new Headers($this->mock),
        ];
        $termResults = [];

        /** @var \Imposter\Server\Imposter\Matcher\Term\AbstractTerm $term */
        foreach ($terms as $term) {
            try {
                $term->match($request);
            } catch (\Exception $e) {
                $termResults[] = new TermResult($term, $e);
            }
        }

        return $termResults;
    }
}
