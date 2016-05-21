<?php

namespace AppBundle\Command\Core;

use AppBundle\Service\Scrapynizer\QueueCommandServiceInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class QueueCommand extends ContainerAwareCommand
{
    /**
     * @var int
     */
    protected $defaultLimit = 100;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var QueueCommandServiceInterface
     */
    protected $service;

    protected function configure()
    {
        $this
            ->setName($this->name)
            ->addOption('limit', 'l', InputOption::VALUE_OPTIONAL, 'limit', $this->defaultLimit);
    }
    /**
     * @return QueueCommandServiceInterface
     */
    protected function getService()
    {
        return $this->service;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = microtime(true);

        $exitCode = $this->getService()->execute((int)$input->getOption('limit'));

        $duration = microtime(true) - $startTime;
        $memory = memory_get_usage(true);
        $peak = memory_get_peak_usage(true);

        $output->writeln([
            "<info>Execute:  $duration</info>",
            "<info>Memory:   $memory B</info>",
            "<info>Peak:     $peak B</info>",
        ]);

        return $exitCode;
    }
}