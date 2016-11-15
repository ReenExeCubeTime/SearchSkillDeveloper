<?php

namespace ReenExe\Scrapynizer\Analyzer;

interface ContentAnalyzerInterface
{
    /**
     * @param $path
     * @param $html
     * @return mixed
     */
    public function analyze($path, $html);
}
