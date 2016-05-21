<?php

namespace AppBundle\Service\Scrapynizer;

use ReenExe\Scrapynizer\Scraper\ListScraperInterface;

class QueueCommandService implements QueueCommandServiceInterface
{
    /**
     * @var ListScraperInterface
     */
    private $service;

    public function __construct(ListScraperInterface $service)
    {
        $this->service = $service;
    }

    public function execute($limit)
    {
        return $this->service->process($limit);
    }
}
