<?php

namespace AppBundle\Service\Scrap;

use Doctrine\DBAL\Connection;

abstract class AbstractListScraper extends Scraper
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var ProfileListContentStorageInterface
     */
    protected $profileListStorage;

    protected $contentAnalyzer;

    public function __construct(
        Connection $connection,
        ProfileListContentStorageInterface $profileListStorage,
        ListContentAnalyzerInterface $contentAnalyzer
    ) {
        $this->connection = $connection;
        $this->profileListStorage = $profileListStorage;
        $this->contentAnalyzer = $contentAnalyzer;
    }
}
