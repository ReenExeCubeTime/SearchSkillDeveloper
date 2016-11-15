<?php

namespace ReenExe\Scrapynizer\Finder;

use Symfony\Component\DomCrawler\Crawler;

interface CollectionFinderInterface
{
    /**
     * @param Crawler $crawler
     * @return array
     */
    public function getList(Crawler $crawler);
}
