<?php

namespace AppBundle\Service\Scrapynizer\Common;

use ReenExe\Scrapynizer\Analyzer\ContentAnalyzerInterface;
use ReenExe\Scrapynizer\Content\ContainerInterface;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractProfileContentAnalyzer implements ContentAnalyzerInterface
{
    /**
     * @var AbstractProfileRepository
     */
    private $repository;

    /**
     * ContentAnalyzer constructor.
     * @param AbstractProfileRepository $repository
     */
    public function __construct(AbstractProfileRepository $repository)
    {
        $this->repository = $repository;
    }

    public function analyze($path, ContainerInterface $container)
    {
        $profile = $this->getProfile($container->getCrawler());

        $this->repository->save($path, $profile);
    }

    abstract protected function getProfile(Crawler $crawler);
}
