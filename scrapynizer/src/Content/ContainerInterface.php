<?php

namespace ReenExe\Scrapynizer\Content;

use Symfony\Component\DomCrawler\Crawler;

interface ContainerInterface
{
    /**
     * @return string
     */
    public function getString();

    /**
     * @return Crawler
     */
    public function getCrawler();
}
