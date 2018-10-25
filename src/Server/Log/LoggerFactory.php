<?php
declare(strict_types=1);

namespace Imposter\Server\Log;

use Imposter\Common\Di;
use Imposter\Common\Di\InterfaceFactory;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

/**
 * Class LoggerFactory
 * @package Imposter\Log
 */
class LoggerFactory implements InterfaceFactory
{
    /**
     * @param \Imposter\Common\Di $di
     * @return Logger
     */
    public function create(Di $di): Logger
    {
        $handler = $this->getHandler($di);
        $handler->setFormatter(new HtmlFormatter());
        $log = new Logger('Imposter');
        $log->pushHandler($handler);

        return $log;
    }

    /**
     * @param Di $di
     * @return AbstractProcessingHandler
     */
    private function getHandler(Di $di): AbstractProcessingHandler
    {
        return new class($di->get(LogRepository::class)) extends AbstractProcessingHandler {

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