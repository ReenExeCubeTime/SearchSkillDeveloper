<?php

namespace AppBundle\Command\D;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AnalyzeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('pd:analyaze');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = microtime(true);

        $duration = microtime(true) - $startTime;
        $memory = memory_get_usage(true);
        $peak = memory_get_peak_usage(true);

        $output->writeln([
            "<info>Execute:  $duration</info>",
            "<info>Memory:   $memory B</info>",
            "<info>Peak:     $peak B</info>",
        ]);
    }
}
