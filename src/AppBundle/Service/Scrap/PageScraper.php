<?php

namespace AppBundle\Service\Scrap;

use AppBundle\Service\AbstractQueueService;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

class PageScraper extends AbstractQueueService
{
    /**
     * @var ProfileContentStorage
     */
    private $contentStorage;

    /**
     * @var PageProcessInterface
     */
    private $pageProcess;

    /**
     * @var Client
     */
    private $client;

    public function __construct(
        ProfileContentStorage $contentStorage,
        PageProcessInterface $pageProcess,
        Client $client
    ) {
        $this->contentStorage = $contentStorage;
        $this->pageProcess = $pageProcess;
        $this->client = $client;
    }

    protected function process($limit)
    {
        $pages = $this->pageProcess->getNextList($limit);

        if (empty($pages)) {
            return self::END;
        }

        /* @var $promises PromiseInterface[] */
        $promises = [];

        foreach ($pages as $path) {
            $promises[] = $this->client
                ->getAsync($path)
                ->then(function (ResponseInterface $response) use ($path) {
                    $html = $response->getBody()->getContents();
                    $this->contentStorage->save($path, $html);
                    $this->pageProcess->exclude($path);
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
    }

    protected function createCache()
    {
        $this->contentStorage->create();
    }
}