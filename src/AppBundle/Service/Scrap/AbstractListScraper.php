<?php

namespace AppBundle\Service\Scrap;

use Doctrine\DBAL\Connection;

abstract class AbstractListScraper extends Scraper
{
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
        ProfileListContentStorageInterface $profileListStorage,
        ListContentAnalyzerInterface $contentAnalyzer,
        PagePathQueue $pagePathQueue
    ) {
        $this->profileListStorage = $profileListStorage;
        $this->contentAnalyzer = $contentAnalyzer;
        $this->pagePathQueue = $pagePathQueue;
    }
}
