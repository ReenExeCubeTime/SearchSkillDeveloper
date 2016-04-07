<?php

namespace AppBundle\Service\Scrap;

use AppBundle\Service\AbstractQueueService;

abstract class Scraper extends AbstractQueueService
{

    /**
     * @var
     */
    protected $client;

    /**
     * @return \GuzzleHttp\Client
     */
    protected function getClient()
    {
        return $this->client;
    }
}