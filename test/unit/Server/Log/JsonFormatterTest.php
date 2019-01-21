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

class JsonFormatterTest extends TestCase
{
    /**
     * @test
     */
    public function format()
    {
        $formatter = new JsonFormatter();
        $record = $this->getRecord();
        $this->assertEquals(json_encode($record, JSON_PRETTY_PRINT)."\n", $formatter->format($record));

        $formatter = new JsonFormatter(JsonFormatter::BATCH_MODE_JSON, false);
        $record = $this->getRecord();
        $this->assertEquals(json_encode($record, JSON_PRETTY_PRINT), $formatter->format($record));
    }

    /**
     * @return array Record
     */
    protected function getRecord($level = Logger::WARNING, $message = 'test', $context = array())
    {
        return [
            'message' => $message,
            'context' => $context,
            'level' => $level,
            'level_name' => Logger::getLevelName($level),
            'channel' => 'test',
            'datetime' => \DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true))),
            'extra' => [],
        ];
    }
}