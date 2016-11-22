<?php

namespace AppBundle\Service\Scrapynizer;

use ReenExe\Scrapynizer\Analyzer\ContentAnalyzerInterface;
use ReenExe\Scrapynizer\Content\ContainerInterface;

class SequenceContentAnalyzer implements ContentAnalyzerInterface
{
    /**
     * @var LinkCollectionAnalyzerInterface
     */
    private $linkCollectionAnalyzer;

    public function __construct(LinkCollectionAnalyzerInterface $linkCollectionAnalyzer)
    {
        $this->linkCollectionAnalyzer = $linkCollectionAnalyzer;
    }

    public function analyze($path, ContainerInterface $container)
    {
        $this->linkCollectionAnalyzer->analyze($container->getCrawler());
    }
}
