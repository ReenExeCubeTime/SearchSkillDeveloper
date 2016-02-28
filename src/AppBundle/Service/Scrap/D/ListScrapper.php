<?php

namespace AppBundle\Service\Scrap\D;

use AppBundle\Service\Scrap\AbstractListScraper;
use Symfony\Component\DomCrawler\Crawler;

class ListScrapper extends AbstractListScraper
{
    protected function process($limit)
    {
        $client = $this->getClient();

        if ($last = $this->getLast()) {
            $nextListPath = $this->getNextPage(new Crawler($last));

            if (empty($nextListPath)) {
                return self::END;
            }

        } else {
            $nextListPath = '/developers/';
        }

        do {
            try {
                $html =  $client->get($nextListPath)->getBody()->getContents();
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                return self::END;
            }

            $this->saveListCache($nextListPath, $html);

            $crawler = new Crawler($html);

            $this->pushPageQueue($this->getPageLinkCollection($crawler));

            $nextListPath = $this->getNextPage($crawler);
        } while (--$limit && $nextListPath);
    }

    protected function createCache()
    {
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS `skill_site_list_cache` (
                `id` INT(11) PRIMARY KEY AUTO_INCREMENT,
                `path` VARCHAR(255),
                `value` MEDIUMBLOB,
                UNIQUE KEY (`path`)
            );
        ");

        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS `skill_site_page_queue` (
                `path` VARCHAR(255) PRIMARY KEY,
                `process` TINYINT DEFAULT 0
            );
        ");
    }

    private function getLast()
    {
        return $this->connection
            ->executeQuery("
                SELECT `value` FROM `skill_site_list_cache`
                ORDER BY `id` DESC
                LIMIT 1;
            ")
            ->fetchColumn();
    }

    private function getNextPage(Crawler $crawler)
    {
        $nextLinkCrawler = $crawler->filter('.next a');

        if ($nextLinkCrawler->count()) {
            return $nextLinkCrawler->attr('href');
        }
    }

    private function getPageLinkCollection(Crawler $crawler)
    {
        return $crawler
            ->filter('.profile')
            ->each(function (Crawler $crawler) {
                return $crawler->attr('href');
            });
    }

    private function pushPageQueue(array $pathCollection)
    {
        $this->connection->beginTransaction();

        foreach ($pathCollection as $path) {
            $this->connection->executeQuery("
                INSERT INTO `skill_site_page_queue` (`path`)
                VALUES (:path)
            ", compact('path'));
        }

        $this->connection->commit();
    }

    private function saveListCache($path, $value)
    {
        $this->connection->executeQuery("
            INSERT INTO `skill_site_list_cache` (`path`, `value`)
            VALUES (:path, :value)
        ", compact('path', 'value'));
    }
}
