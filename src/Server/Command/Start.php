<?php
declare(strict_types=1);

namespace Imposter\Server\Command;

use Imposter\Common\Di;

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

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $di = new Di();
        $di->set('output', $output);

        $port = $input->getOption('port');
        if (!$port) {
            $port = Server::PORT;
        }

        $server = new Server($di);
        $server->run($port);
    }
}
