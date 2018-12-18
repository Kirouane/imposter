<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 12/5/18
 * Time: 6:37 PM
 */

namespace Imposter\Server\Log;


use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class HtmlFormatterTest extends TestCase
{
    /**
     * @test
     */
    public function format()
    {
        $view = \Mockery::mock(\Symfony\Component\Templating\EngineInterface::class);
        $view->shouldReceive('render')->with(\Mockery::any(), \Mockery::on(function(array $dataView) {
            self::assertArrayHasKey('title', $dataView);
            self::assertArrayHasKey('matchResults', $dataView);
            self::assertSame(['key' => 'value'], $dataView['matchResults']);
            return true;
        }));

        $formatter = new HtmlFormatter($view);
        $formatter->format([
            'message' => 'test',
            'level' => Logger::ERROR,
            'level_name' => 'ERROR',
            'datetime' => new \DateTime(),
            'context' => [
                'matchResult' => ['key' => 'value']
            ]

        ]);
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}