<?php

namespace AppBundle\Command\Core;

use AppBundle\Service\AbstractQueueService;

class RunTimeQueueCommand extends QueueCommand
{
    /**
     * @param string $name
     * @param AbstractQueueService $service
     */
    public function __construct($name, AbstractQueueService $service)
    {
        $this->name = $name;
        $this->service = $service;
        parent::__construct();
    }
}
