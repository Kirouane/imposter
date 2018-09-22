<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/22/18
 * Time: 2:48 PM
 */

namespace Imposter;


use Imposter\Repository\HttpMock;

/**
 * Class ImposterState
 * @package Imposter
 */
class ImposterState
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
     * @throws \Exception
     */
    public function __construct()
    {
        if (!self::$di) {
            self::$di = new Di();
        }
    }

    /**
     * @return HttpMock
     */
    public static function getRepository()
    {
        return self::$di->get(HttpMock::class);
    }

    /**
     * @return Di
     */
    public static function getDi(): Di
    {
        return self::$di;
    }

    /**
     * @throws \Exception
     */
    public function capture()
    {
        if (!self::$initialized) {
            if (!self::getRepository()->isStarted()) {
                self::getRepository()->restart();
            }
            self::getRepository()->drop();
            self::$initialized = true;
        }
    }

    public function release()
    {
        self::$initialized = false;
    }
}