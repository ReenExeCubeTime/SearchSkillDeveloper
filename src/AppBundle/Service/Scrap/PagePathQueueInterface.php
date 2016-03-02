<?php

namespace AppBundle\Service\Scrap;

interface PagePathQueueInterface
{
    public function create();

    public function push(array $list);
}
