<?php

namespace AppBundle\Service\Scrap;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

class PageScraper extends Scraper
{
    /**
     * @var ProfileContentStorage
     */
    private $contentStorage;

    /**
     * @var PageProcessInterface
     */
    private $pageProcess;

    public function __construct(
        ProfileContentStorage $contentStorage,
        PageProcessInterface $pageProcess
    ) {
        $this->contentStorage = $contentStorage;
        $this->pageProcess = $pageProcess;
    }

    protected function process($limit)
    {
        $pages = $this->pageProcess->getNextList($limit);

        if (empty($pages)) {
            return self::END;
        }

        /* @var $promises PromiseInterface[] */
        $promises = [];
        $client = $this->getClient();
        foreach ($pages as $path) {
            $promises[] = $client
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