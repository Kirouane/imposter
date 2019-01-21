<?php
declare(strict_types=1);

namespace Imposter\Server\Command;

use Imposter\Common\Container;
use Imposter\Server\Server;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
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
        $this
            ->setName('start')
            ->addArgument('port', InputArgument::REQUIRED)
            ->addOption('config', 'c', InputOption::VALUE_REQUIRED);

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
        $container = new Container();
        $configPath = $input->getOption('config');

        if ($configPath && !is_file($configPath)) {
            $container->get('logger')->info("The file $configPath doesn't exist.");
            throw new \InvalidArgumentException("The file $configPath doesn't exist.");
        }

        if ($configPath) {
            $container->set('config.path', realpath($configPath));
        }

        $container->set('output', $output);
        $container->set('port', $input->getArgument('port'));

        $server = new Server($container);

        try {
            $server->run($container->get('config')->getPort());
        } catch (\Throwable $e) {
            $container->get('logger')->critical($e);
        }
    }
}
