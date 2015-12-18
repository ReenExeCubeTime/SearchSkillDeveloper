<?php

namespace AppBundle\Service\SkillSite;

use AppBundle\Service\AbstractQueueService;

class AnalyzeStructureService extends AbstractQueueService
{
    protected function process($limit)
    {
        $source = $this->getAllSourceSkills();

        $map = [];

        $skillExistMap = [];

        foreach ($source as $row) {
            $skills = json_decode($row['skills'], true);

            foreach ($skills as $skill) {
                $skillExistMap[$skill['name']] = true;

                $map[$row['id']][$skill['name']] = $skill['score'];
            }
        }

        $skillNames = array_keys($skillExistMap);

        $skillNameIdMap = array_flip($this->addSkillClasifier($skillNames));

        $this->addDeveloperToSkillList($map, $skillNameIdMap);
    }

    private function getAllSourceSkills()
    {
        return $this->connection->fetchAll("
            SELECT `id`, `skills`
            FROM `developer`
            WHERE `skills` <> '[]'
        ");
    }

    protected function createCache()
    {
        $this->createTableStructure();
    }

    private function createTableStructure()
    {
        $this->connection->exec("DROP TABLE IF EXISTS `skills`;");
        $this->connection->exec("DROP TABLE IF EXISTS `developer_to_skill`;");

        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS `skills`(
                `id` INT(11) PRIMARY KEY,
                `name` VARCHAR(255),
                UNIQUE KEY (`name`)
            ) DEFAULT CHARACTER SET=`utf8` COLLATE=`utf8_bin`;
        ");

        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS `developer_to_skill`(
                `id` INT PRIMARY KEY AUTO_INCREMENT,
                `developer_id` INT(11),
                `skill_id` INT(11),
                `score` TINYINT
            );
        ");
    }

    private function addSkillClasifier(array $list)
    {
        $result = array_combine(range(1, count($list)), $list);

        foreach ($result as $id => $name) {

            $this->connection
                ->insert(
                    'skills',
                    [
                        'id' => $id,
                        'name' => $name
                    ]
                );
        }

        return $result;
    }

    private function addDeveloperToSkillList(array $map, array $skillNameIdMap)
    {
        $this->connection->beginTransaction();
        foreach ($map as $developerId => $skillNameScoreMap) {
            foreach ($skillNameScoreMap as $skillName => $score) {
                $this->connection->insert('developer_to_skill', [
                    'developer_id' => $developerId,
                    'skill_id' => $skillNameIdMap[$skillName],
                    'score' => $score,
                ]);
            }
        }
        $this->connection->commit();
    }
}