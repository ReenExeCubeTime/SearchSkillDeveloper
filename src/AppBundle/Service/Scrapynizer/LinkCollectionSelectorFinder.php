<?php

namespace AppBundle\Service\Scrapynizer;

use ReenExe\Scrapynizer\Finder\CollectionSelectorFinder;
use Symfony\Component\DomCrawler\Crawler;

class LinkCollectionSelectorFinder extends CollectionSelectorFinder
{
    public function __construct($selector)
    {
        $closure = function (Crawler $crawler) {
            return $crawler->attr('href');
        };

        parent::__construct($selector, $closure);
    }
}
