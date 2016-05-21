<?php

namespace AppBundle\Service\Scrap;

use Symfony\Component\DomCrawler\Crawler;

class ListContentAnalyzer implements ListContentAnalyzerInterface
{
    /**
     * @var string
     */
    private $firstPage;

    /**
     * @var string
     */
    private $nextPageSelector;

    /**
     * @var string
     */
    private $profileLinkSelector;

    /**
     * ListContentAnalyzer constructor.
     * @param $firstPage
     * @param $nextPageSelector
     * @param $profileLinkSelector
     */
    public function __construct($firstPage, $nextPageSelector, $profileLinkSelector)
    {
        $this->firstPage = $firstPage;
        $this->nextPageSelector = $nextPageSelector;
        $this->profileLinkSelector = $profileLinkSelector;
    }

    public function getFirstPage()
    {
        return $this->firstPage;
    }

    public function getNextPage(Crawler $crawler)
    {
        $nextLinkCrawler = $crawler->filter($this->nextPageSelector);

        return $nextLinkCrawler->count() ? $nextLinkCrawler->attr('href') : false;
    }

    public function getPageLinkCollection(Crawler $crawler)
    {
        return $crawler
            ->filter($this->profileLinkSelector)
            ->each(function (Crawler $crawler) {
                return $crawler->attr('href');
            });
    }
}