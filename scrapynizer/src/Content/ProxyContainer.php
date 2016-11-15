<?php

namespace ReenExe\Scrapynizer\Content;

use Symfony\Component\DomCrawler\Crawler;

class ProxyContainer extends AbstractContainer
{
    /**
     * ProxyContainer constructor.
     * @param $string
     */
    public function __construct($string)
    {
        $this->string = $string;
    }

    /**
     * @return Crawler
     */
    public function getCrawler()
    {
        if ($this->crawler === null) {
            $this->crawler = new Crawler($this->string);
        }

        return $this->crawler;
    }
}
