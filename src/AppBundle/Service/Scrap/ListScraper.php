<?php

namespace AppBundle\Service\Scrap;

use AppBundle\Service\AbstractQueueService;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ListScraper extends AbstractQueueService
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

    /**
     * @var Client
     */
    private $client;

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
                $html =  $this->client->get($nextListPath)->getBody()->getContents();
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
