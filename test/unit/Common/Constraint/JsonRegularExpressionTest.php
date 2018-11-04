<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 10/18/18
 * Time: 5:56 PM
 */

namespace Imposter\Common\Constraint;


use Imposter\Common\Constraint\JsonRegularExpression;
use PHPUnit\Framework\TestCase;

class JsonRegularExpressionTest extends TestCase
{



    public function matchesProvider()
    {
        return [
            ['', '', false],
            ['{}', '{}', true],
            ['{"a":"a"}', '{"a":"a"}', true],
            ['{"a":"a"}', '{"a":"b"}', false],
            ['{"a":"a", "b":"b"}', '{"b":"b"}', true],
            ['{"a":"a", "b":"b"}', '{"a":"a"}', true],
            ['{"a":"a", "b":"b"}', '{"a":"a", "b":"b"}', true],
            ['{"a":"a", "b":"b"}', '{"b":"b", "a":"a"}', true],

            ['[]', '[]', true],
            ['["a"]', '["a"]', true],
            ['["a"]', '[]', true],
            ['[]', '["a"]', false],
            ['["a","b"]', '["a","b"]', true],
            ['["a","b"]', '["a"]', true],
            ['[,"b"]', '["a","b"]', false],
            ['["a","b"]', '["b","a"]', true],

            ['{"a":["a"]}', '{"a":["a"]}', true],
            ['{"a":["a", "b"]}', '{"a":["a"]}', true],//17
            ['{"a":["a"]}', '{"a":["a", "b"]}', false],

            ['{"a":{"b":"b"}}', '{"a":{"b":"b"}}', true],
            ['{"a":{"b":"b"}}', '{"a":{"b":"c"}}', false],
            ['{"a":{"b":"b", "c":"c"}}', '{"a":{"c":"c"}}', true],

            ['{"abc":"abc"}', '{"abc":"[a-z]{3}"}', true],
            ['{"abc":"ab"}', '{"abc":"[a-z]{3}"}', false],
        ];
    }

    /**
     * @dataProvider matchesProvider
     */
    public function testMatches($value, $pattern, $matches)
    {
        $predicate = new JsonRegularExpression($pattern);
        $reflection = new \ReflectionClass($predicate);
        $method = $reflection->getMethod('matches');
        $method->setAccessible(true);

        self::assertSame($matches, $method->invoke($predicate, $value));
    }
}