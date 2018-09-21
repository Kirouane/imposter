<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/21/18
 * Time: 12:25 PM
 */

namespace Imposter\Log;


use Imposter\Di;
use Monolog\Formatter\HtmlFormatter;
use Monolog\Logger;

/**
 * Class LoggerFactory
 * @package Imposter\Log
 */
class LoggerFactory implements \Imposter\Di\InterfaceFactory
{
    public function create(Di $di)
    {

        $handler = $this->getHandler($di);
        $handler->setFormatter(new HtmlFormatter());
        $log = new Logger('name');
        $log->pushHandler($handler);

        return $log;
    }

    /**
     * @param Di $di
     * @return \Monolog\Handler\AbstractProcessingHandler
     */
    private function getHandler(Di $di)
    {
        return new class($di->get(LogRepository::class)) extends \Monolog\Handler\AbstractProcessingHandler {

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
        };
    }
}