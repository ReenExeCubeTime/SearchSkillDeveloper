<?php

namespace AppBundle\Command\W;

use AppBundle\Command\Core\QueueCommand;

class ScrapSkillSiteListCommand extends QueueCommand
{
    protected $name = 'w:scrap:skill:site:list';

    protected function getService()
    {
        return $this->getContainer()->get('pw.scrap_list_service');
    }
}