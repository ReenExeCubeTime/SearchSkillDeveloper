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
        $this->setName('pd:analyze');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = microtime(true);

        /* @var $connection Connection */
        $connection = $this->getContainer()->get('doctrine')->getConnection();

        $cities = <<<'SQL'
            DROP TABLE IF EXISTS `city`;
            CREATE TABLE `city` (
                `alias` VARCHAR(255),
                `name` VARCHAR(255),
                `count` INT(11),
                UNIQUE KEY `UNIQUE_NAME` (`name`)
            );
            INSERT INTO `city` (`name`) VALUES
            ('kyiv', 'Киев'),
            ('kharkiv', 'Харьков'),
            ('lviv', 'Львов'),
            ('dnipro', 'Днепропетровск'),
            ('odesa', 'Одесса'),
            ('ukraine', 'Украина'),
            ('vinnytsya', 'Винница'),
            ('mykolaiv', 'Николаев'),
            ('zaporizhzhya', 'Запорожье'),
            ('khmelnytskyi', 'Хмельницкий'),
            ('ivano-frankivsk', 'Ивано-Франковск'),
            ('cherkasy', 'Черкассы'),
            ('chernivtsi', 'Черновцы'),
            ('zhytomyr', 'Житомир'),
            ('chernihiv', 'Чернигов'),
            ('simferopol', 'Симферополь'),
            ('donetsk', 'Донецк'),
            ('sevastopol', 'Севастополь');
SQL;

        $connection->exec($cities);

        $profileStatement = $connection->prepare('
            SELECT *
            FROM `pd_profile`
            WHERE JSON_LENGTH(`skills`) > 0
            AND `city` IN (
                SELECT `name` FROM `city`
            )
        ');

        $profileStatement->execute();

        $sourceProfiles = $profileStatement->fetchAll(\PDO::FETCH_ASSOC);
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

        $connection->exec('
            DROP TABLE IF EXISTS `skill`;
            CREATE TABLE `skill` (
                `alias` VARCHAR(255),
                `name` VARCHAR(255),
                `count` INT(1)
            );
        ');

        $skillCreateStatement = $connection->prepare('
            INSERT INTO `skill`
            VALUE (:alias, :name, :count);
        ');
        $connection->beginTransaction();
        foreach ($skillAvailableAliasCountMap as $alias => $count) {
            $skillCreateStatement->execute([
                'alias' => $alias,
                'name' => $skillAvailableAliasNameMap[$alias],
                'count' => $count,
            ]);
        }
        $connection->commit();

        $connection->exec('
            DROP TABLE IF EXISTS `profile`;
            CREATE TABLE `profile` (
                `id` INT PRIMARY KEY,
                `link` VARCHAR(255),
                `city` VARCHAR(255),
                `title` VARCHAR(255),
                `salary` INT(11),
                `experience` TINYINT(1),
                `description` TEXT,
                `skills` TEXT
            );
        ');

        $profileIdMap = array_column($sourceProfiles, null, 'id');
        $profiles = [];
        foreach ($profileIdSkillAliases as $profileId => $skills) {
            $available = array_intersect_key(array_flip($skills), $skillAvailableAliasCountMap);

            if ($available) {
                $sourceProfile = $profileIdMap[$profileId];
                $profiles[] = [
                    'id' => $profileId,
                    'link' => 'https://djinni.co' . $sourceProfile['path'],
                    'city' => $sourceProfile['city'],
                    'title' => $sourceProfile['title'],
                    'salary' => $sourceProfile['salary'],
                    'experience' => $sourceProfile['experience_year'],
                    'description' => $sourceProfile['experience_description'],
                    'skills' => json_encode(array_keys($available)),
                ];
            }
        }

        $availableProfileCount = count($profiles);
        $output->writeln("<info>Available profiles:</info> $availableProfileCount");

        $profileCreateStatement = $connection->prepare('
            INSERT INTO `profile`
            VALUE (:id, :link, :city, :title, :salary, :experience, :description, :skills);
        ');

        $connection->beginTransaction();
        foreach ($profiles as $profile) {
            $profileCreateStatement->execute($profile);
        }
        $connection->commit();

        $connection->exec('
            UPDATE `city`
            INNER JOIN (
                SELECT `city`, COUNT(*) AS `count`
                FROM `profile`
                GROUP BY `city`
            ) AS `profile_city` ON (`city`.`name` = `profile_city`.`city`)
            SET `city`.`count` = `profile_city`.`count`;
        ');

        $output->writeln([
            "<info>Execute:  $duration</info>",
            "<info>Memory:   $memory B</info>",
            "<info>Peak:     $peak B</info>",
        ]);
    }
}
