<?php

namespace AppBundle\Command\D;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
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

        $skillStatement = $connection->prepare('
            SELECT *
            FROM `pd_profile`
            WHERE JSON_LENGTH(`skills`) > 0
            AND `city` IN (
                SELECT `name` FROM `city`
            )
        ');

        $skillStatement->execute();

        $sourceProfiles = $skillStatement->fetchAll(\PDO::FETCH_ASSOC);
        $sourceProfileIdSkillsMap = array_column($sourceProfiles, 'skills', 'id');

        $skillAliasNameCountMap = [];
        $skillAliasCountMap = [];
        $profileIdSkillAliases = [];
        foreach ($sourceProfileIdSkillsMap as $profileId => $sourceSkill) {
            foreach (array_slice(json_decode($sourceSkill, true), 0, 10) as $skill) {
                if ($alias = preg_replace('/[^0-9a-z#+]/i', '', strtolower($skill['name']))) {
                    $skillAliasNameCountMap[$alias][$skill['name']] = isset($skillAliasNameCountMap[$alias][$skill['name']])
                        ? $skillAliasNameCountMap[$alias][$skill['name']] + 1
                        : 1;
                    $skillAliasCountMap[$alias] = isset($skillAliasCountMap[$alias])
                        ? $skillAliasCountMap[$alias] + 1
                        : 1;
                    $profileIdSkillAliases[$profileId][] = $alias;
                }
            }
        }

        $skillAvailableAliasNameMap = [];
        $skillAvailableAliasCountMap = [];
        foreach ($skillAliasCountMap as $alias => $count) {
            if ($count > 2) {
                $nameCountMap = $skillAliasNameCountMap[$alias];

                arsort($nameCountMap);

                $skillAvailableAliasNameMap[$alias] = key($nameCountMap);
                $skillAvailableAliasCountMap[$alias] = array_sum($nameCountMap);
            }
        }

        arsort($skillAvailableAliasCountMap);

        $availableSkillCount = count($skillAvailableAliasNameMap);

        $output->writeln("<info>Available skills:</info> $availableSkillCount");

        $top = 50;
        $output->writeln("<info>Top $top skills:</info>");
        $rows = [];
        foreach (array_slice($skillAvailableAliasCountMap, 0, $top) as $alias => $count) {
            $rows[] = [$alias, $skillAvailableAliasNameMap[$alias], $count];
        }
        $table = new Table($output);
        $table
            ->setHeaders(['Alias', 'Name', 'Count'])
            ->setRows($rows);
        $table->render();

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
