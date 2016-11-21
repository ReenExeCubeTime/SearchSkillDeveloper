<?php

namespace ReenExe\Scrapynizer\Scraper;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use ReenExe\Scrapynizer\Analyzer\ContentAnalyzerInterface;
use ReenExe\Scrapynizer\Content\Container;
use ReenExe\Scrapynizer\Pager\PaginationHunterInterface;
use ReenExe\Scrapynizer\Repository\SequenceContentRepositoryInterface;
use Symfony\Component\DomCrawler\Crawler;

class SequenceScraper extends AbstractScraper implements ListScraperInterface
{
    /**
     * @var SequenceContentRepositoryInterface
     */
    protected $repository;

    /**
     * @var PaginationHunterInterface
     */
    protected $pager;

    /**
     * @var ContentAnalyzerInterface
     */
    protected $analyzer;

    /**
     * @param Client $client
     * @param SequenceContentRepositoryInterface $repository
     * @param PaginationHunterInterface $pager
     * @param ContentAnalyzerInterface $analyzer
     */
    public function __construct(
        Client $client,
        SequenceContentRepositoryInterface $repository,
        PaginationHunterInterface $pager,
        ContentAnalyzerInterface $analyzer
    ) {
        $this->client = $client;
        $this->repository = $repository;
        $this->pager = $pager;
        $this->analyzer = $analyzer;
    }

    public function process($limit)
    {
        if ($last = $this->repository->getLast()) {
            $nextPath = $this->pager->getNextPage(new Crawler($last));

            if (empty($nextPath)) {
                return self::STATUS_END;
            }

        } else {
            $nextPath = $this->pager->getFirstPage();
        }

        do {
            try {
                $html =  $this->client->get($nextPath)->getBody()->getContents();
            } catch (ClientException $e) {
                return self::STATUS_END;
            }

            $crawler = new Crawler($html);

            $this->repository->save($nextPath, $html);

            $this->analyzer->analyze($nextPath, new Container($html, $crawler));

            $nextPath = $this->pager->getNextPage($crawler);
        } while (--$limit && $nextPath);

        return self::STATUS_PROGRESS;
    }
}
