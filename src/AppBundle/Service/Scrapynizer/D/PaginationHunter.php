<?php

namespace AppBundle\Service\Scrapynizer\D;

use ReenExe\Scrapynizer\Pager\PaginationHunterInterface;
use Symfony\Component\DomCrawler\Crawler;

class PaginationHunter implements PaginationHunterInterface
{
    /**
     * @return string
     */
    public function getFirstPage()
    {
        // TODO: Implement getFirstPage() method.
    }

    /**
     * @param Crawler $crawler
     * @return string|false
     */
    public function getNextPage(Crawler $crawler)
    {
        // TODO: Implement getNextPage() method.
    }
}
