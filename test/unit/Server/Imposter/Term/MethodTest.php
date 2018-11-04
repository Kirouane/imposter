<?php

namespace Imposter\Server\Imposter\Term;

use Imposter\Common\Model\Mock;
use Imposter\Common\PredicateFactory;
use Imposter\Server\Imposter\Matcher\Term\Method;
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
        $mock    = new Mock(1);

        $term = new \Imposter\Server\Imposter\Matcher\Term\Method($mock);
        self::assertNull($term->match($request));
    }

    /**
     * @test
     */
    public function matchWithConstraintSuccess()
    {
        $request = \Mockery::mock(\RingCentral\Psr7\ServerRequest::class);
        $request->shouldReceive('getMethod')->andReturn('GET')->once();
        $mock = new Mock(1);
        $mock->setRequestMethod(new IsIdentical('GET'));

        $term = new \Imposter\Server\Imposter\Matcher\Term\Method($mock);
        self::assertNull($term->match($request));
    }

    /**
     * @test
     */
    public function matchWithConstraintFail()
    {
        $request = \Mockery::mock(\RingCentral\Psr7\ServerRequest::class);
        $request->shouldReceive('getMethod')->andReturn('GET')->once();
        $mock = new Mock(1);
        $mock->setRequestMethod(new IsIdentical('POST'));

        $term = new Method($mock);

        $e = null;
        try {
            $term->match($request);
        } catch (\Exception $e) {
        }

        self::assertInstanceOf(\Exception::class, $e);
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}
