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

    /**
     * @var ListContentAnalyzerInterface
     */
    protected $contentAnalyzer;

    /**
     * @var PagePathQueue
     */
    protected $pagePathQueue;

    public function __construct(
        Connection $connection,
        ProfileListContentStorageInterface $profileListStorage,
        ListContentAnalyzerInterface $contentAnalyzer,
        PagePathQueue $pagePathQueue
    ) {
        $this->connection = $connection;
        $this->profileListStorage = $profileListStorage;
        $this->contentAnalyzer = $contentAnalyzer;
        $this->pagePathQueue = $pagePathQueue;
    }
}
