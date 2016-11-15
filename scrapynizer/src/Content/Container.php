<?php

namespace ReenExe\Scrapynizer\Content;

use Symfony\Component\DomCrawler\Crawler;

class Container implements ContainerInterface
{
    /**
     * @var string
     */
    private $string;

    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * Container constructor.
     * @param string $string
     * @param Crawler $crawler
     */
    public function __construct($string, Crawler $crawler)
    {
        $this->string = $string;
        $this->crawler = $crawler;
    }

    /**
     * @return string
     */
    public function getString()
    {
        return $this->string;
    }

    /**
     * @return Crawler
     */
    public function getCrawler()
    {
        return $this->crawler;
    }
}
