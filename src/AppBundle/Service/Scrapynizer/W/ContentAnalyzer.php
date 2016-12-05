<?php

namespace AppBundle\Service\Scrapynizer\W;

use AppBundle\Service\Scrapynizer\Common\AbstractProfileContentAnalyzer;
use Symfony\Component\DomCrawler\Crawler;

class ContentAnalyzer extends AbstractProfileContentAnalyzer
{
    protected function getProfile(Crawler $crawler)
    {
        $body = $crawler->filter('body');

        $skillSource = '';
        $skillList = $body->filter("body h2.cut-top:contains('навички') + ul");

        if ($skillList->count()) {
            $skillSource = $skillList
                ->first()
                ->filter('span.text-muted')
                ->text();

        }

        return [
            'title' => $this->getTitle($crawler),
            'full_name' => $body->filter('h1.cut-top')->text(),
            'description' => $crawler->filter('head meta[property="og:description"]')->attr('content'),
            'city' => 'Київ',
            'skill_source' => strip_tags($skillSource),
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
