<?php

namespace AppBundle\Command\Core;

use AppBundle\Service\AbstractQueueService;

class RunTimeQueueCommand extends QueueCommand
{
    /**
     * @var AbstractQueueService
     */
    private $service;

    /**
     * RunTimeQueueCommand constructor.
     * @param AbstractQueueService $service
     */
    public function __construct(AbstractQueueService $service)
    {
        $this->service = $service;
        parent::__construct();
    }

    /**
     * @return AbstractQueueService
     */
    protected function getService()
    {
        return $this->service;
    }
}
