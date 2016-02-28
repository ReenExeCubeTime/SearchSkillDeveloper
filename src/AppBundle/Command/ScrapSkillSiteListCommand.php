<?php

namespace AppBundle\Command;

use AppBundle\Command\Core\QueueComand;

class ScrapSkillSiteListCommand extends QueueComand
{
    protected function configure()
    {
        $this->setName('scrap:skill:site:list');
    }

    protected function getService()
    {
        return $this->getContainer()->get('scrap_list_service');
    }
}