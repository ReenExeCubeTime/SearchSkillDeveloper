<?php

namespace AppBundle\Command\Core;

use AppBundle\Service\AbstractQueueService;
use AppBundle\Service\Scrapynizer\QueueCommandServiceInterface;

class RunTimeQueueCommand extends QueueCommand
{
    /**
     * @param string $name
     * @param AbstractQueueService $service
     */
    public function __construct($name, QueueCommandServiceInterface $service)
    {
        $this->name = $name;
        $this->service = $service;
        parent::__construct();
    }
}
