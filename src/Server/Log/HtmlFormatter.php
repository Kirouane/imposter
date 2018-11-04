<?php
declare(strict_types=1);

namespace Imposter\Server\Log;


use Imposter\Server\Imposter\MatchResult;
use Imposter\Server\Imposter\MatchResults;
use Monolog\Formatter\NormalizerFormatter;
use Monolog\Logger;
use PHPUnit\Framework\TestFailure;

/**
 * Class HtmlFormatter
 * @package Imposter\Log
 */
class HtmlFormatter extends NormalizerFormatter
{
    /**
     * Translates Monolog log levels to html color priorities.
     */
    protected static $logLevels = [
        Logger::DEBUG     => '#cccccc',
        Logger::INFO      => '#468847',
        Logger::NOTICE    => '#3a87ad',
        Logger::WARNING   => '#c09853',
        Logger::ERROR     => '#f0ad4e',
        Logger::CRITICAL  => '#FF7708',
        Logger::ALERT     => '#C12A19',
        Logger::EMERGENCY => '#000000',
    ];

    /**
     * Creates an HTML table row
     *
     * @param  string $th       Row header content
     * @param  TdElement $td       Row standard cell content
     * @return string
     */
    protected function addRow($th, TdElement $td): string
    {
        $th = htmlspecialchars($th, ENT_NOQUOTES);

        return "<tr style=\"padding: 4px;text-align: left;\">\n<th style=\"vertical-align: top;background: #ccc;color: #000\" width=\"100\">$th:</th>\n<td style=\"padding: 4px;text-align: left;vertical-align: top;background: #eee;color: #000\">".$td."</td>\n</tr>";
    }

    /**
     * Creates an HTML table
     *
     * @param $record
     * @return string
     */
    private function addTable($record): string
    {
        $embeddedTable = '<table cellspacing="1" width="100%">';

        /** @var array $record */
        foreach ($record as $key => $value) {
            $value = TdElement::escaped($this->convertToString($value));
            $embeddedTable .= $this->addRow($key, $value);
        }
        $embeddedTable .= '</table>';

        return $this->addRow('Context', TdElement::default($embeddedTable));
    }

    /**
     * Create a HTML h1 tag
     *
     * @param  string $title Text to be in the h1
     * @param  int    $level Error level
     * @param  int    $time Error level
     * @return string
     */
    protected function addTitle($title, $level, $time): string
    {
        $title = htmlspecialchars($title, ENT_NOQUOTES);

        return '<h3 style="margin-bottom: 0; background: '.self::$logLevels[$level].';color: #ffffff;padding: 5px;" class="monolog-output">' . $time . ' - ' . $title.'</h3>';
    }

    /** @noinspection PhpMissingParentCallCommonInspection */
    /**
     * Formats a log record.
     *
     * @param  array $record A record to format
     * @return mixed The formatted record
     */
    public function format(array $record)
    {
        $output = $this->addTitle($record['level_name'], $record['level'], $record['datetime']->format($this->dateFormat));
        $output .= '<table cellspacing="1" width="100%" class="monolog-output">';

        $output .= $this->addRow('Message', TdElement::escaped($record['message']));

        if (isset($record['context']['matchResult'])) {
            $output .= $this->addMatchResults($record['context']['matchResult']);
            unset($record['context']['matchResult']);
        }

        if ($record['context']) {
            $this->addTable($record['context']);
        }

        if ($record['extra']) {
            $this->addTable($record['extra']);
        }

        return $output.'</table>';
    }

    /** @noinspection PhpMissingParentCallCommonInspection */
    /**
     * Formats a set of log records.
     *
     * @param  array $records A set of records to format
     * @return mixed The formatted set of records
     */
    public function
    formatBatch(array $records)
    {
        $message = '';
        foreach ($records as $record) {
            $message .= $this->format($record);
        }

        return $message;
    }

    /**
     * @param $data
     * @return mixed|string
     */
    protected function convertToString($data)
    {
        if (null === $data || is_scalar($data)) {
            return (string) $data;
        }

        $data = $this->normalize($data);
        if (PHP_VERSION_ID >= 50400) {
            return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        return str_replace('\\/', '/', json_encode($data));
    }


    /**
     * @param MatchResults $matchResults
     * @return string
     */
    private function addMatchResults(MatchResults $matchResults): string
    {
        $output = '';
        /** @var MatchResult $matchResult */
        foreach ($matchResults as $matchResult) {
            $mock = $matchResult->getMock();
            $output .= "<tr style=\"padding: 4px;text-align: left;\">\n";
            $output .=  "<th style=\"vertical - align: top;background: #ccc;color: #000\" width=\"100\">localhost:{$mock->getPort()}</th>\n";
            $output .= '<td style="padding: 4px;text-align: left;vertical-align: top;background: #eee;color: #000">';
            $output .=      $mock->getFile() . ':' .$mock->getLine() . '<br />';
            foreach ($matchResult->getExceptions() as $exception) {
                $output .= '<pre>' . TestFailure::exceptionToString($exception) . '</pre>';
            }
            $output .=  "</td>\n";
            $output .= '</tr>';
        }

        return $output;
    }
}