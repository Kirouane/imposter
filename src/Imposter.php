<?php
declare(strict_types=1);

namespace Imposter;

use Imposter\Imposter\Prediction\CallTime\AbstractCallTime;
use Imposter\Imposter\Prediction\CallTime\AtLeast;
use Imposter\Imposter\Prediction\CallTime\AtMost;
use Imposter\Imposter\Prediction\CallTime\Equals;
use Imposter\Model\Mock;
use Imposter\Repository\HttpMock;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsIdentical;

/**
 * Class Imposter
 * @package Imposter
 */
class Imposter
{
    /**
     * @var
     */
    private static $initialized = false;

    /**
     * @var Di
     */
    private static $di;

    /**
     * @var Imposter[]
     */
    private static $httpImposters = [];

    /**
     * @param int $port
     * @return ImposterHttp
     * @throws \Exception
     */
    public static function mock(int $port): ImposterHttp
    {
        if (!self::$di) {
            self::$di = new Di();
        }

        if (!self::$initialized) {
            if (!self::getRepository()->isStarted()) {
                self::getRepository()->restart();
            }
            self::getRepository()->drop();
            self::$initialized = true;
        }

        return self::$httpImposters[] = new ImposterHttp($port, self::$di->get(HttpMock::class));
    }

    /**
     * @throws \Exception
     */
    public static function close()
    {
        self::$initialized = false;
        /** @var ImposterHttp $imposter */
        foreach (self::$httpImposters as $imposter) {
            $imposter->resolve();
        }

        self::$httpImposters = [];
    }

    /**
     * @return HttpMock
     */
    public static function getRepository()
    {
        return self::$di->get(HttpMock::class);
    }
}
