<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 11/24/18
 * Time: 5:32 PM
 */

namespace Imposter\Server\Log;


use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

/**
 * Class Handler
 * @package Imposter\Server\Log
 */
class Handler extends AbstractProcessingHandler {

    /**
     * @var LogRepository
     */
    private $repository;

    /**
     *  constructor.
     * @param LogRepository $repository
     * @param int $level
     * @param bool $bubble
     */
    public function __construct(LogRepository $repository, $level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->repository = $repository;
    }


    /**
     * Writes the record down to the log of the implementing handler
     *
     * @param  array $record
     * @return void
     */
    protected function write(array $record)
    {
        $this->repository->add($record['formatted']);
    }
}