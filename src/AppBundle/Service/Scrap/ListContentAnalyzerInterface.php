<?php

namespace AppBundle\Service\Scrap;

use Symfony\Component\DomCrawler\Crawler;

interface ListContentAnalyzerInterface
{
    /**
     * @return string
     */
    public function getFirstPage();

    /**
     * @param Crawler $crawler
     * @return string|false
     */
    public function getNextPage(Crawler $crawler);

    /**
     * @param Crawler $crawler
     * @return array
     */
    public function getPageLinkCollection(Crawler $crawler);
}
