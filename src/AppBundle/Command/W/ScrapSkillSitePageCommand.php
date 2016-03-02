<?php

namespace AppBundle\Command\W;

use AppBundle\Command\Core\QueueCommand;

class ScrapSkillSitePageCommand extends QueueCommand
{
    protected $name = 'w:scrap:skill:site:page';

    protected function getService()
    {
        return $this->getContainer()->get('pw.scrap_page_service');
    }
}
