<?php

namespace AppBundle\Command\D;

use AppBundle\Command\Core\QueueCommand;

class ScrapSkillSiteListCommand extends QueueCommand
{
    protected $name = 'd:scrap:skill:site:list';

    protected function getService()
    {
        return $this->getContainer()->get('pd.scrap_list_service');
    }
}