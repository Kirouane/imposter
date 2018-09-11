<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/3/18
 * Time: 12:36 PM
 */

namespace Imposter\Command;

use Imposter\Di;

use Imposter\Server;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Start extends Command
{
    const DEFAULT_PORT = 8080;

    protected function configure()
    {
        $this->setName('start');
        $this->addOption('port', 'p', InputOption::VALUE_OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $di = new Di();
        $di->set('output', $output);

        $port = $input->getOption('port');
        if (!$port) {
            $port = self::DEFAULT_PORT;
        }

        $server = new Server($di);
        $server->run($port);
    }
}