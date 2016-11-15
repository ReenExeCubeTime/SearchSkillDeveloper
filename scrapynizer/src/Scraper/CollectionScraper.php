<?php

namespace ReenExe\Scrapynizer\Scraper;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;
use ReenExe\Scrapynizer\Analyzer\ContentAnalyzerInterface;
use ReenExe\Scrapynizer\Repository\PathCollectionRepositoryInterface;

class CollectionScraper extends AbstractScraper implements ListScraperInterface
{
    /**
     * @var PathCollectionRepositoryInterface
     */
    protected $repository;

    /**
     * @var ContentAnalyzerInterface
     */
    protected $analyzer;

    /**
     * @param Client $client
     * @param PathCollectionRepositoryInterface $repository
     * @param ContentAnalyzerInterface $analyzer
     */
    public function __construct(
        Client $client,
        PathCollectionRepositoryInterface $repository,
        ContentAnalyzerInterface $analyzer
    ) {
        $this->client = $client;
        $this->repository = $repository;
        $this->analyzer = $analyzer;
    }

    public function process($limit)
    {
        $pages = $this->repository->getNext($limit);

        if (empty($pages)) {
            return self::STATUS_END;
        }

        /* @var $promises PromiseInterface[] */
        $promises = [];

        foreach ($pages as $path) {
            $promises[] = $this->client
                ->getAsync($path)
                ->then(function (ResponseInterface $response) use ($path) {
                    $html = $response->getBody()->getContents();
                    $this->analyzer->analyze($path, $html);
                    $this->repository->exclude($path);
                });
        }

        do {
            foreach ($promises as $key => $promise) {
                if ($promise->getState() === PromiseInterface::FULFILLED) {
                    unset($promises[$key]);
                } else {
                    $promise->wait();
                }
            }
        } while ($promises);

        return self::STATUS_PROGRESS;
    }
}