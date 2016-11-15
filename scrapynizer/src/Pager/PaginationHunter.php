<?php

namespace ReenExe\Scrapynizer\Pager;

use Symfony\Component\DomCrawler\Crawler;

class PaginationHunter implements PaginationHunterInterface
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
     * @param $firstPage
     * @param $nextPageSelector
     */
    public function __construct($firstPage, $nextPageSelector)
    {
        $this->firstPage = $firstPage;
        $this->nextPageSelector = $nextPageSelector;
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
}
