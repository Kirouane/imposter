<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/22/18
 * Time: 2:48 PM
 */

namespace Imposter;


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


    public function init()
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
    }
}