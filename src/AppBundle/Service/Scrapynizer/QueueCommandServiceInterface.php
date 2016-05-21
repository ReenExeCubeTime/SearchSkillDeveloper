<?php

namespace AppBundle\Service\Scrapynizer;

interface QueueCommandServiceInterface
{
    public function execute($limit);
}
