<?php

namespace Imposter\Log;

/**
 * Class TdElement
 * @package Imposter\Log
 */
class TdElement
{
    /** @var string $content */
    private $content;

    /**
     * TdElement constructor.
     */
    private function __construct(string $content)
    {
        $this->content = $content;
    }

    /**
     * @param string $content
     * @return TdElement
     */
    public static function default(string $content)
    {
        return new self($content);
    }

    /**
     * @param string $content
     * @return TdElement
     */
    public static function escaped(string $content)
    {
        return new self('<pre>' . self::escapeHtml($content) . '</pre>');
    }

    /**
     * @param string $content
     * @return string
     */
    private static function escapeHtml(string $content)
    {
        return htmlspecialchars($content, ENT_NOQUOTES, 'UTF-8');
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->content;
    }
}