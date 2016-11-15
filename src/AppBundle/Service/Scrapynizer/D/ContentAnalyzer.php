<?php

namespace AppBundle\Service\Scrapynizer\D;

use ReenExe\Scrapynizer\Analyzer\ContentAnalyzerInterface;
use ReenExe\Scrapynizer\Content\ContainerInterface;

class ContentAnalyzer implements ContentAnalyzerInterface
{
    public function analyze($path, ContainerInterface $container)
    {
        throw new \Exception(__METHOD__);
    }
}
