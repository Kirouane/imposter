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
class JsonFormatter extends \Monolog\Formatter\JsonFormatter
{
    /**
     * @param mixed $data
     * @param bool $ignoreErrors
     * @return string
     */
    protected function toJson($data, $ignoreErrors = false)
    {
        if ($ignoreErrors) {
            return @$this->jsonEncode($data);
        }

        return $this->jsonEncode($data);
    }

    private function jsonEncode($data)
    {
        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}