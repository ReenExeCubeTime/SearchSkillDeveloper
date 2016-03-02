<?php

namespace AppBundle\Service\Scrap\D;

use AppBundle\Service\Scrap\ListContentAnalyzerInterface;
use Symfony\Component\DomCrawler\Crawler;

class ListContentAnalyzer implements ListContentAnalyzerInterface
{
    public function getFirstPage()
    {
        return '/developers/';
    }

    public function getNextPage(Crawler $crawler)
    {
        $nextLinkCrawler = $crawler->filter('.next a');

        return $nextLinkCrawler->count() ? $nextLinkCrawler->attr('href') : false;
    }

    public function getPageLinkCollection(Crawler $crawler)
    {
        return $crawler
            ->filter('.profile')
            ->each(function (Crawler $crawler) {
                return $crawler->attr('href');
            });
    }
}
