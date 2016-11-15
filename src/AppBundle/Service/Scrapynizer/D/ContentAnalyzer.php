<?php

namespace AppBundle\Service\Scrapynizer\D;

use ReenExe\Scrapynizer\Analyzer\ContentAnalyzerInterface;
use ReenExe\Scrapynizer\Content\ContainerInterface;
use Symfony\Component\DomCrawler\Crawler;

class ContentAnalyzer implements ContentAnalyzerInterface
{
    /**
     * @var ProfileRepository
     */
    private $repository;

    /**
     * ContentAnalyzer constructor.
     * @param ProfileRepository $repository
     */
    public function __construct(ProfileRepository $repository)
    {
        $this->repository = $repository;
    }

    public function analyze($path, ContainerInterface $container)
    {
        $profile = $this->getProfile($container->getCrawler());

        $this->repository->save($path, $profile);
    }

    private function getProfile(Crawler $crawler)
    {
        $body = $crawler->filter('body');

        $header = $body->filter('.page-header');

        $breadcrumbs = $header
            ->filter('.breadcrumb li span a')
            ->each(function (Crawler $crawler) {
                return trim($crawler->text());
            });

        $descriptions = $body
            ->filter('.container > .row .profile')
            ->each(function (Crawler $crawler) {
                return trim($crawler->text());
            });

        $default = array_fill(0, 3, '');

        $descriptions = $descriptions + $default;

        $breadcrumbs = $breadcrumbs + $default;

        $data = [
            'city' => $breadcrumbs[1],
            'category' => $breadcrumbs[2],
            'title' => trim($header->filter('h1')->text()),
            'salary' => substr(
                trim($header->filter('.profile-details-salary')->text()),
                1
            ),
            'experience_year' => (int)$body->filter('.before-hint')->text(),
            'experience_description' => $descriptions[0],
            'achievement' => $descriptions[1],
            'expect' => $descriptions[2],
            'skills' => json_encode($this->getSkills($body)),
        ];

        return $data;
    }

    private function getSkills(Crawler $crawler)
    {
        return $crawler
            ->filter('table.skills-table tr')
            ->each(function (Crawler $crawler) {
                $name = trim($crawler->filter('td')->first()->text());

                $score = (int) $crawler->filter('div.skill')->attr('data-score');

                return compact('name', 'score');
            });
    }
}
