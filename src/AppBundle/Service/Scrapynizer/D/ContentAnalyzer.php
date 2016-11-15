<?php

namespace AppBundle\Service\Scrapynizer\D;

use ReenExe\Scrapynizer\Analyzer\ContentAnalyzerInterface;
use ReenExe\Scrapynizer\Content\ContainerInterface;
use Symfony\Component\DomCrawler\Crawler;

class ContentAnalyzer implements ContentAnalyzerInterface
{
    public function analyze($path, ContainerInterface $container)
    {
        /**
            CREATE TABLE IF NOT EXISTS `developer`(
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
         */

        dump($this->getProfile($container->getCrawler()));

        throw new \Exception(__METHOD__);
    }

    private function getProfile(Crawler $crawler)
    {
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
            'skills' => json_encode($this->getSkills($body)),
        ];

        return $data;
    }

    private function getSkills(Crawler $crawler)
    {
        return $crawler
            ->filter('table.skills-table tr')
            ->each(function (Crawler $crawler) {
                $name = trim($crawler->filter('td')->first()->text());

                $score = (int) $crawler->filter('div.skill')->attr('data-score');

                return compact('name', 'score');
            });
    }
}
