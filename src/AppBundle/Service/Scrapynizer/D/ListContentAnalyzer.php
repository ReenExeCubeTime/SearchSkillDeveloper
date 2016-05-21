<?php

namespace AppBundle\Service\Scrapynizer\D;

use ReenExe\Scrapynizer\Analyzer\ListContentAnalyzerInterface;
use Symfony\Component\DomCrawler\Crawler;

class ListContentAnalyzer implements ListContentAnalyzerInterface
{
    /**
     * @param $nextPath
     * @param $html
     * @param Crawler $crawler
     * @return mixed
     */
    public function analyze($nextPath, $html, Crawler $crawler)
    {
        // TODO: Implement analyze() method.
    }

}
