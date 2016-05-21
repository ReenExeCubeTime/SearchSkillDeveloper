<?php

namespace AppBundle\Service\Scrapynizer;

use ReenExe\Scrapynizer\Finder\CollectionSelectorFinder;
use Symfony\Component\DomCrawler\Crawler;

class LinkCollectionAnalyzer implements LinkCollectionAnalyzerInterface
{
    /**
     * @var CollectionSelectorFinder
     */
    private $finder;

    /**
     * @var QueueRepositoryInterface
     */
    private $repository;

    public function __construct(CollectionSelectorFinder $finder, QueueRepositoryInterface $repository)
    {
        $this->finder = $finder;
        $this->repository = $repository;
    }

    public function analyze(Crawler $crawler)
    {
        if ($list = $this->finder->getList($crawler)) {
            $this->repository->push($list);
        }
    }
}
