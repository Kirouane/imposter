<?php
declare(strict_types=1);

namespace Imposter\Server\Imposter;

use Imposter\Server\Imposter\Term\AbstractTerm;
use Imposter\Server\Imposter\Term\Body;
use Imposter\Server\Imposter\Term\Headers;
use Imposter\Server\Imposter\Term\Method;
use Imposter\Server\Imposter\Term\Path;
use Imposter\Common\Model\Mock;
use PHPUnit\Framework\AssertionFailedError;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Matcher
 * @package Imposter\Imposter
 */
class Matcher
{
    /**
     * @var \Imposter\Common\Model\Mock
     */
    private $mock;

    /**
     * Matcher constructor.
     * @param \Imposter\Common\Model\Mock $mock
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

        /** @var \Imposter\Server\Imposter\Term\AbstractTerm $term */
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
