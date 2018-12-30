<?php /** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */
declare(strict_types=1);

namespace Imposter;

use Imposter\Client\Imposter\MockBuilder;
use Imposter\Common\Config;

/**
 * Trait PhpunitTrait
 * @package Imposter
 */
trait ImposterTrait
{
    /**
     * @param int $port
     * @param int|null $imposterPort
     * @return MockBuilder
     * @throws \Exception
     */
    public function openImposter(int $port, $imposterPort = Config::DEFAULT_PORT): MockBuilder
    {
        return ImposterFactory::get($imposterPort)->mock($port);
    }

    /**
     * @param int|null $imposterPort
     * @throws \Exception
     */
    public function closeImposers($imposterPort = Config::DEFAULT_PORT)
    {
        ImposterFactory::get($imposterPort)->close();
    }

}