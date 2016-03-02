<?php

namespace AppBundle\Service\Scrap\W;

use AppBundle\Service\Scrap\ListContentAnalyzerInterface;
use Symfony\Component\DomCrawler\Crawler;

class ListContentAnalyzer implements ListContentAnalyzerInterface
{
    public function getFirstPage()
    {
        return '/resumes-it/';
    }

    public function getNextPage(Crawler $crawler)
    {
        $nextLinkCrawler = $crawler->filter('.perpageNavigation a');

        return $nextLinkCrawler->count() ? $nextLinkCrawler->attr('href') : false;
    }

    public function getPageLinkCollection(Crawler $crawler)
    {
        return $crawler
            ->filter('.tblSearch .ttl a.bf')
            ->each(function (Crawler $crawler) {
                return $crawler->attr('href');
            });
    }
}
