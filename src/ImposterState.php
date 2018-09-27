<?php
declare(strict_types=1);

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
    private $initialized = false;

    /**
     * @var Di
     */
    private $di;


    /**
     * @throws \Exception
     */
    public function __construct()
    {
        if (!$this->di) {
            $this->di = new Di();
        }
    }

    /**
     * @return HttpMock
     */
    public function getRepository(): HttpMock
    {
        return $this->di->get(HttpMock::class);
    }

    /**
     * @return Di
     */
    public function getDi(): Di
    {
        return $this->di;
    }

    /**
     * @throws \Exception
     */
    public function capture()
    {
        if (!$this->initialized) {
            if (!$this->getRepository()->isStarted()) {
                $this->getRepository()->restart();
            }
            $this->getRepository()->drop();
            $this->initialized = true;
        }
    }

    public function release()
    {
        $this->initialized = false;
    }

    /**
     *
     */
    public function stop()
    {
        if (!$this->getRepository()->isStarted()) {
            $this->getRepository()->stop();
        }
    }
}