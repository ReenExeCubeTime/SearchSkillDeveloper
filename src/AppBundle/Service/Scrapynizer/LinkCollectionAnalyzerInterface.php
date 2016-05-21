<?php

namespace AppBundle\Service\Scrapynizer;

use Symfony\Component\DomCrawler\Crawler;

interface LinkCollectionAnalyzerInterface
{
    /**
     * @param Crawler $crawler
     */
    public function analyze(Crawler $crawler);
}
