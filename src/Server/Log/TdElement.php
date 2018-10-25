<?php
declare(strict_types=1);

namespace Imposter\Server\Log;

/**
 * Class TdElement
 * @package Imposter\Log
 */
class TdElement
{
    /**
     * @var string
     */
    private $content;

    /**
     * TdElement constructor.
     * @param string $content
     */
    private function __construct(string $content)
    {
        $this->content = $content;
    }

    /**
     * @param string $content
     * @return TdElement
     */
    public static function default(string $content): TdElement
    {
        return new self($content);
    }

    /**
     * @param string $content
     * @return TdElement
     */
    public static function escaped(string $content): TdElement
    {
        return new self('<pre>' . self::escapeHtml($content) . '</pre>');
    }

    /**
     * @param string $content
     * @return string
     */
    private static function escapeHtml(string $content): string
    {
        return htmlspecialchars($content, ENT_NOQUOTES);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->content;
    }
}