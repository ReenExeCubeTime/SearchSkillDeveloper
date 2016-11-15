<?php

namespace ReenExe\Scrapynizer\Analyzer;

use Symfony\Component\DomCrawler\Crawler;

interface ListContentAnalyzerInterface
{
    /**
     * @param $path
     * @param $html
     * @param Crawler $crawler
     * @return mixed
     */
    public function analyze($path, $html, Crawler $crawler);
}
