<?php

namespace AppBundle\Service\Scrap;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ListScraper extends Scraper
{
    /**
     * @var ProfileListContentStorageInterface
     */
    private $profileListStorage;

    /**
     * @var ListContentAnalyzerInterface
     */
    private $contentAnalyzer;

    /**
     * @var PagePathQueueInterface
     */
    private $pagePathQueue;

    public function __construct(
        ProfileListContentStorageInterface $profileListStorage,
        ListContentAnalyzerInterface $contentAnalyzer,
        PagePathQueueInterface $pagePathQueue,
        Client $client
    ) {
        $this->profileListStorage = $profileListStorage;
        $this->contentAnalyzer = $contentAnalyzer;
        $this->pagePathQueue = $pagePathQueue;
        $this->client = $client;
    }

    protected function process($limit)
    {
        $client = $this->getClient();

        if ($last = $this->profileListStorage->getLast()) {
            $nextListPath = $this->contentAnalyzer->getNextPage(new Crawler($last));

            if (empty($nextListPath)) {
                return self::END;
            }

        } else {
            $nextListPath = $this->contentAnalyzer->getFirstPage();
        }

        do {
            try {
                $html =  $client->get($nextListPath)->getBody()->getContents();
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                return self::END;
            }

            $this->profileListStorage->save($nextListPath, $html);

            $crawler = new Crawler($html);

            $this->pagePathQueue->push($this->contentAnalyzer->getPageLinkCollection($crawler));

            $nextListPath = $this->contentAnalyzer->getNextPage($crawler);
        } while (--$limit && $nextListPath);
    }

    protected function createCache()
    {
        $this->profileListStorage->create();
        $this->pagePathQueue->create();
    }
}
