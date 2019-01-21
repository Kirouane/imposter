<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 1/15/19
 * Time: 5:26 PM
 */

namespace Imposter\Server\Imposter;


use Imposter\Common\Model\Mock;
use PHPUnit\Framework\TestCase;

class MatchResultTest extends TestCase
{

    /**
     * @test
     */
    public function jsonSerialize()
    {
        $mock = new Mock(1);
        $mock->setFile('file');
        $mock->setLine(1);
        $matchResult = new MatchResult($mock, []);
        self::assertInternalType('array', json_decode(json_encode($matchResult), true));
    }
}
