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
     * @var Imposter[]
     */
    private static $httpImposters = [];

    /**
     * @var ImposterState
     */
    private static $state;

    /**
     * @param int $port
     * @return ImposterHttp
     * @throws \Exception
     */
    public static function mock(int $port): ImposterHttp
    {
        self::$state = self::$state ?: new ImposterState();
        self::$state->capture();
        return self::$httpImposters[] = new ImposterHttp($port, self::$state->getDi()->get(HttpMock::class));
    }

    /**
     * @throws \Exception
     */
    public static function close()
    {

        self::$state->release();
        /** @var ImposterHttp $imposter */
        foreach (self::$httpImposters as $imposter) {
            $imposter->resolve();
        }

        self::$httpImposters = [];
    }

}
