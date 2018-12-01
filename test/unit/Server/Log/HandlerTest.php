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

class HandlerTest extends TestCase
{
    /**
     * @test
     */
    public function format()
    {
        $repository = \Mockery::mock(\Imposter\Server\Log\LogRepository::class);
        $repository->shouldReceive('add')->once()->with(\Mockery::on(function($formatted){
            self::assertTrue($formatted);
            return true;
        }));
        $handler = new Handler($repository);
        $classReflection = new \ReflectionClass($handler);
        $method = $classReflection->getMethod('write');
        $method->setAccessible(true);

        $method->invoke($handler, ['formatted' => true]);

    }

    public function tearDown()
    {
        \Mockery::close();
    }
}