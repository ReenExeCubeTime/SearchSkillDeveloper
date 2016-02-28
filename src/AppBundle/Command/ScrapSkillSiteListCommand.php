<?php

namespace AppBundle\Command;

use AppBundle\Command\Core\QueueComand;

class ScrapSkillSiteListCommand extends QueueComand
{
    protected function configure()
    {
        $this->setName('d:scrap:skill:site:list');
    }

    protected function getService()
    {
        return $this->getContainer()->get('pd.scrap_list_service');
    }
}