<?php

namespace ReenExe\Scrapynizer\Scraper;

use GuzzleHttp\Client;

abstract class AbstractScraper
{
    const STATUS_PROGRESS = 0;

    const STATUS_END = 1;

    /**
     * @var Client
     */
    protected $client;
}
