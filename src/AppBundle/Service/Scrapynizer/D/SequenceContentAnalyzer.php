<?php

namespace AppBundle\Service\Scrapynizer\D;

use AppBundle\Service\Scrapynizer\LinkCollectionAnalyzerInterface;
use ReenExe\Scrapynizer\Analyzer\ListContentAnalyzerInterface;
use Symfony\Component\DomCrawler\Crawler;

class SequenceContentAnalyzer implements ListContentAnalyzerInterface
{
    /**
     * @var LinkCollectionAnalyzerInterface
     */
    private $linkCollectionAnalyzer;

    public function __construct(LinkCollectionAnalyzerInterface $linkCollectionAnalyzer)
    {
        $this->linkCollectionAnalyzer = $linkCollectionAnalyzer;
    }

    public function analyze($path, $html, Crawler $crawler)
    {
        $this->linkCollectionAnalyzer->analyze($crawler);
    }
}
