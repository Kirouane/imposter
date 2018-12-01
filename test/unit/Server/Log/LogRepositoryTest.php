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

class LogRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function getAll()
    {
        $repository = new LogRepository();
        $repository->add('text');
        self::assertSame(['text'], $repository->getAll());
    }
}