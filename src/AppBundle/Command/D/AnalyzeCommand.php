<?php

namespace AppBundle\Command\D;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Connection;

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

        /* @var $connection Connection */
        $connection = $this->getContainer()->get('doctrine')->getConnection();

        $cities = <<<'SQL'
            DROP TABLE IF EXISTS `city`;
            CREATE TABLE `city` (
                `name` VARCHAR(255)
            );
            INSERT INTO `city` (`name`) VALUES
            ('Киев'),
            ('Харьков'),
            ('Львов'),
            ('Днепропетровск'),
            ('Одесса'),
            ('Украина'),
            ('Винница'),
            ('Николаев'),
            ('Запорожье'),
            ('Хмельницкий'),
            ('Ивано-Франковск'),
            ('Черкассы'),
            ('Черновцы'),
            ('Житомир'),
            ('Чернигов'),
            ('Симферополь'),
            ('Донецк'),
            ('Севастополь');
SQL;

        $connection->exec($cities);

        $output->writeln([
            "<info>Execute:  $duration</info>",
            "<info>Memory:   $memory B</info>",
            "<info>Peak:     $peak B</info>",
        ]);
    }
}
