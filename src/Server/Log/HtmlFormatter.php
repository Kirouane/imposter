<?php
declare(strict_types=1);

namespace Imposter\Server\Log;


use Monolog\Formatter\NormalizerFormatter;
use Monolog\Logger;
use Symfony\Component\Templating\EngineInterface;

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
     * @var EngineInterface
     */
    private $view;

    /**
     * @param EngineInterface $view
     */
    public function __construct(EngineInterface $view)
    {
        parent::__construct();
        $this->view = $view;
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
       $dataView = [
           'message' => $record['message'],
            'title' => [
                'backgroundColor' => self::$logLevels[$record['level']],
                'title' => $record['level_name'],
                'time' => $record['datetime']->format($this->dateFormat)
            ],
            'matchResults' => null
        ];

        if (isset($record['context']['matchResult'])) {
            $dataView['matchResults'] = $record['context']['matchResult'];
        }

        return $this->view->render(__DIR__ . '/record.phtml', $dataView);
    }
}