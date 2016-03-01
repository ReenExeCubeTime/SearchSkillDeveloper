<?php

namespace AppBundle\Service\Scrap;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;
use Doctrine\DBAL\Connection;

abstract class AbstractPageScraper extends Scraper
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var ProfileContentStorageInterface
     */
    protected $contentStorage;

    public function __construct(Connection $connection, ProfileContentStorageInterface $contentStorage)
    {
        $this->connection = $connection;
        $this->contentStorage = $contentStorage;
    }

    protected function process($limit)
    {
        $pages = array_column($this->getPages($limit), 'path');

        if (empty($pages)) {
            return self::END;
        }

        /* @var $promises PromiseInterface[] */
        $promises = [];
        $client = $this->getClient();
        foreach ($pages as $path) {
            $promises[] = $client
                ->getAsync($path)
                ->then(function (ResponseInterface $response) use ($path) {
                    $html = $response->getBody()->getContents();
                    $this->contentStorage->save($path, $html);
                    $this->updateProcess($path);
                });
        }

        do {
            foreach ($promises as $key => $promise) {
                if ($promise->getState() === PromiseInterface::FULFILLED) {
                    unset($promises[$key]);
                } else {
                    $promise->wait();
                }
            }
        } while ($promises);
    }

    protected function createCache()
    {
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS `skill_site_page_cache` (
                `path` VARCHAR(255) PRIMARY KEY,
                `value` MEDIUMBLOB
            );
        ");
    }

    private function getPages($limit)
    {
        return $this->connection
            ->fetchAll("
                SELECT `path` FROM `skill_site_page_queue`
                WHERE `process` = 0
                LIMIT $limit;
            ");
    }

    private function updateProcess($path)
    {
        return $this->connection
            ->exec("
                UPDATE `skill_site_page_queue`
                SET `process` = 1
                WHERE `path` = '$path'
            ");
    }
}