<?php

namespace AppBundle\Service;

use AppBundle\Service\Scrapynizer\QueueCommandServiceInterface;

abstract class AbstractQueueService implements QueueCommandServiceInterface
{
    const END = 1;

    /**
     * @param $limit
     * @return mixed
     */
    public function execute($limit)
    {
        $this->createCache();
        return $this->process($limit);
    }

    abstract protected function createCache();

    abstract protected function process($limit);
}