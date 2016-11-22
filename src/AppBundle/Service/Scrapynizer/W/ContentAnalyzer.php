<?php

namespace AppBundle\Service\Scrapynizer\W;

use AppBundle\Service\Scrapynizer\Common\AbstractProfileContentAnalyzer;
use Symfony\Component\DomCrawler\Crawler;

class ContentAnalyzer extends AbstractProfileContentAnalyzer
{
    protected function getProfile(Crawler $crawler)
    {
        return [
            'title' => $this->getTitle($crawler),
            'city' => 'Київ',
        ];
    }

    private function getTitle(Crawler $crawler)
    {
        $title = $crawler->filter('title')->text();

        if (preg_match('/«(.*?)»/', $title, $matches)) {
            return $matches[1];
        }

        return $title;
    }
}
