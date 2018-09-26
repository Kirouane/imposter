<?php /** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */
declare(strict_types=1);

namespace Imposter;

/**
 * Trait PhpunitTrait
 * @package Imposter
 */
trait PhpunitTrait
{
    /**
     * @param int $port
     * @return ImposterHttp
     */
    public function openImposter(int $port): ImposterHttp
    {
        return Imposter::mock($port);
    }

    /**
     *
     */
    public function closeImposers()
    {
        Imposter::close();
    }
}