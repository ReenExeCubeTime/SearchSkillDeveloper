<?php

namespace ReenExe\Scrapynizer\Scraper;

interface ListScraperInterface
{
    /**
     * @param $limit
     * @return mixed
     */
    public function process($limit);
}
