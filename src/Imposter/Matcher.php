<?php
declare(strict_types=1);

namespace Imposter\Imposter;

use Imposter\Imposter\Term\AbstractTerm;
use Imposter\Imposter\Term\Body;
use Imposter\Imposter\Term\Headers;
use Imposter\Imposter\Term\Method;
use Imposter\Imposter\Term\Path;
use Imposter\Model\Mock;
use PHPUnit\Framework\AssertionFailedError;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Matcher
 * @package Imposter\Imposter
 */
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

    /**
     * @param ServerRequestInterface $request
     * @return \Exception[]
     */
    public function match(ServerRequestInterface $request): array
    {
        $terms = [
            new Body($this->mock),
            new Path($this->mock),
            new Method($this->mock),
            new Headers($this->mock),
        ];
        $exceptions = [];

        /** @var AbstractTerm $term */
        foreach ($terms as $term) {
            try {
                $term->match($request);
            } catch (\Exception $e) {
                $exceptions[] = $e;
            }
        }

        return $exceptions;
    }
}
