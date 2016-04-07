<?php

namespace AppBundle\Service;

abstract class AbstractQueueService
{
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