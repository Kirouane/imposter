<?php /** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */
declare(strict_types=1);

namespace Imposter;

use Imposter\Client\Imposter\Builder\Builder;
/**
 * Trait PhpunitTrait
 * @package Imposter
 */
trait PhpunitTrait
{
    /**
     * @param int $port
     * @return Builder
     */
    public function openImposter(int $port): Builder
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