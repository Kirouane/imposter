<?php
declare(strict_types=1);

namespace Imposter\Server\Command;

use Imposter\Common\Container;
use Imposter\Server\Server;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Start
 * @package Imposter\Command
 */
class Start extends Command
{

    protected function configure()
    {
        $this->setName('start');
        $this->addOption('port', 'p', InputOption::VALUE_OPTIONAL);
    }

    /** @noinspection PhpMissingParentCallCommonInspection */
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $di = new Container();
        $di->set('output', $output);

        $port = $input->getOption('port');
        if (!$port) {
            $port = Server::PORT;
        }

        $server = new Server($di);
        $server->run($port);
    }
}
