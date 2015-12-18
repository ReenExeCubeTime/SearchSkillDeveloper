<?php

namespace AppBundle\Service\SkillSite;

use AppBundle\Service\AbstractQueueService;
use Symfony\Component\DomCrawler\Crawler;

class StructureService extends AbstractQueueService
{
    public function clear()
    {
        $this->connection->exec('
            DROP TABLE IF EXISTS `skill_site_structure_queue`;
            DROP TABLE IF EXISTS `skill_site_structure`;
        ');
    }

    protected function process($limit)
    {
        $pages = array_column($this->getPages($limit), 'path');

        if (empty($pages)) {
            return self::END;
        }

        foreach ($pages as $path) {
            $html = $this->getCachedPage($path);

            try {
                $data = $this->getPageData($html);

                $this->savePageData($path, $data);
            } finally {
                $this->updateProcess($path);
            }
        }
    }

    private function getPageData($html)
    {
        $crawler = new Crawler($html);

        $body = $crawler->filter('body');

        $header = $body->filter('.page-header');

        $breadcrumbs = $header
            ->filter('.breadcrumb li span a')
            ->each(function (Crawler $crawler) {
                return trim($crawler->text());
            });

        $descriptions = $body
            ->filter('.container > .row .profile')
            ->each(function (Crawler $crawler) {
                return trim($crawler->text());
            });

        $default = array_fill(0, 3, '');

        $descriptions = $descriptions + $default;

        $breadcrumbs = $breadcrumbs + $default;

        $data = [
            'city' => $breadcrumbs[1],
            'category' => $breadcrumbs[2],
            'title' => trim($header->filter('h1')->text()),
            'salary' => substr(
                trim($header->filter('.profile-details-salary')->text()),
                1
            ),
            'experience_year' => (int)$body->filter('.before-hint')->text(),
            'experience_description' => $descriptions[0],
            'achievement' => $descriptions[1],
            'expect' => $descriptions[2],
            'skills' => json_encode($this->getSkills($body))
        ];

        return $data;
    }

    private function getSkills(Crawler $crawler)
    {
        return $crawler
            ->filter('table.skills-table tr')
            ->each(function (Crawler $crawler) {
                $name = trim($crawler->filter('td')->first()->text());

                $score = (int)$crawler->filter('div.skill')->attr('data-score');

                return compact('name', 'score');
            });
    }

    private function getPages($limit)
    {
        return $this->connection
            ->fetchAll("
                SELECT `path` FROM `skill_site_structure_queue`
                WHERE `process` = 0
                LIMIT $limit;
            ");
    }

    private function getCachedPage($path)
    {
        return $this->connection
            ->fetchColumn("
                SELECT `value` FROM `skill_site_page_cache`
                WHERE `path` = :path
            " ,compact('path'));

    }

    private function updateProcess($path)
    {
        return $this->connection
            ->exec("
                UPDATE `skill_site_structure_queue`
                SET `process` = 1
                WHERE `path` = '$path'
            ");
    }

    private function savePageData($path, array $data)
    {
        $data['path'] = $path;
        $this->connection->insert('skill_site_structure', $data);
    }

    protected function createCache()
    {
        $this->createProcess();
    }

    protected function createProcess()
    {
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS `skill_site_structure_queue` (
                `path` VARCHAR(255) PRIMARY KEY,
                `process` TINYINT DEFAULT 0
            )
              AS
            SELECT `path` FROM `skill_site_page_queue`;
        ");

        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS `skill_site_structure`(
                `id` INT PRIMARY KEY AUTO_INCREMENT,
                `path` VARCHAR(255),
                `city` VARCHAR(255),
                `title` VARCHAR(255),
                `category` VARCHAR(255),
                `salary` INT(11),
                `experience_year` TINYINT(1),
                `experience_description` TEXT,
                `skills` TEXT,
                `achievement` TEXT,
                `expect` TEXT
            ) DEFAULT CHARACTER SET=utf8;
        ");
    }
}