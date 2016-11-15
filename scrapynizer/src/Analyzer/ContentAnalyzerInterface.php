<?php

namespace ReenExe\Scrapynizer\Analyzer;

use ReenExe\Scrapynizer\Content\ContainerInterface;

interface ContentAnalyzerInterface
{
    /**
     * @param $path
     * @param ContainerInterface $container
     * @return mixed
     */
    public function analyze($path, ContainerInterface $container);
}
