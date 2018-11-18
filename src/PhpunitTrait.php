<?php /** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */
declare(strict_types=1);

namespace Imposter;

use Imposter\Client\Imposter\MockBuilder;
/**
 * Trait PhpunitTrait
 * @package Imposter
 */
trait PhpunitTrait
{
    /**
     * @param int $port
     * @return MockBuilder
     */
    public function openImposter(int $port): MockBuilder
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