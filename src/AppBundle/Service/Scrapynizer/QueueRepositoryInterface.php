<?php

namespace AppBundle\Service\Scrapynizer;

interface QueueRepositoryInterface
{
    /**
     * @param array $list
     */
    public function push(array $list);
}
