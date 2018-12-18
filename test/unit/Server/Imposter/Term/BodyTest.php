<?php

namespace Imposter\Server\Imposter\Term;

use Imposter\Common\Model\Mock;
use Imposter\Common\PredicateFactory;
use Imposter\Server\Imposter\Matcher\Term\Body;
use PHPUnit\Framework\Constraint\IsIdentical;

/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/16/18
 * Time: 8:53 PM
 */

class BodyTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function matchNoConstraint()
    {
        $request = new \RingCentral\Psr7\ServerRequest('GET', '/path');
        $mock    = new Mock(1);

        $term = new \Imposter\Server\Imposter\Matcher\Term\Body($mock);
        self::assertNull($term->match($request));
    }

    /**
     * @test
     */
    public function matchWithConstraintSuccess()
    {
        $request = \Mockery::mock(\RingCentral\Psr7\ServerRequest::class);
        $request->shouldReceive('getBody->getContents')->andReturn('{}')->once();
        $request->shouldReceive('getParsedBody')->andReturn([])->once();
        $mock = new Mock(1);
        $mock->setRequestBody(new IsIdentical('{}'));

        $term = new \Imposter\Server\Imposter\Matcher\Term\Body($mock);
        self::assertNull($term->match($request));
    }

    /**
     * @test
     */
    public function matchWithConstraintFail()
    {
        $request = \Mockery::mock(\RingCentral\Psr7\ServerRequest::class);
        $request->shouldReceive('getBody->getContents')->andReturn('[]')->once();
        $request->shouldReceive('getParsedBody');
        $mock = new Mock(1);
        $mock->setRequestBody(new IsIdentical('{}'));

        $term = new \Imposter\Server\Imposter\Matcher\Term\Body($mock);

        $e = null;
        try {
            $term->match($request);
        } catch (\Exception $e) {
        }

        self::assertInstanceOf(\Exception::class, $e);
    }

    /**
     * @test
     */
    public function matchParsedBody()
    {
        $request = \Mockery::mock(\RingCentral\Psr7\ServerRequest::class);
        $request->shouldReceive('getParsedBody')->andReturn(['a' => 'b'])->once();
        $mock = new Mock(1);
        $mock->setRequestBody(new IsIdentical(['a' => 'b']));

        $term = new \Imposter\Server\Imposter\Matcher\Term\Body($mock);
        self::assertNull($term->match($request));
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}
