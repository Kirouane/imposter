<?php

namespace Imposter\Imposter\Term;

use Imposter\Model\Mock;
use Imposter\PredicateFactory;
use PHPUnit\Framework\Constraint\IsIdentical;

/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/16/18
 * Time: 8:53 PM
 */

class MethodTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function matchNoConstraint()
    {
        $request = new \RingCentral\Psr7\ServerRequest('GET', '/path');
        $mock    = new Mock();

        $term = new Method($mock);
        self::assertNull($term->match($request));
    }

    /**
     * @test
     */
    public function matchWithConstraintSuccess()
    {
        $request = \Mockery::mock(\RingCentral\Psr7\ServerRequest::class);
        $request->shouldReceive('getMethod')->andReturn('GET')->once();
        $mock = new Mock();
        $mock->setRequestMethod((new PredicateFactory())->equals('GET'));

        $term = new Method($mock);
        self::assertNull($term->match($request));
    }

    /**
     * @test
     */
    public function matchWithConstraintFail()
    {
        $request = \Mockery::mock(\RingCentral\Psr7\ServerRequest::class);
        $request->shouldReceive('getMethod')->andReturn('GET')->once();
        $mock = new Mock();
        $mock->setRequestMethod((new PredicateFactory())->equals('POST'));

        $term = new Method($mock);

        $e = null;
        try {
            $term->match($request);
        } catch (\Exception $e) {
        }

        self::assertInstanceOf(\Exception::class, $e);
    }
}
