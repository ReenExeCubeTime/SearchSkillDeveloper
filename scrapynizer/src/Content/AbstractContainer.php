<?php

namespace ReenExe\Scrapynizer\Content;

use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractContainer implements ContainerInterface
{
    /**
     * @var string
     */
    protected $string;

    /**
     * @var Crawler
     */
    protected $crawler;

    /**
     * @return string
     */
    public function getString()
    {
        return $this->string;
    }
}
