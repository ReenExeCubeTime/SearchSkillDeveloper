<?php

namespace AppBundle\Command;

use AppBundle\Command\Core\QueueCommand;

class ScrapSkillSitePageCommand extends QueueCommand
{
    protected $name = 'd:scrap:skill:site:page';

    protected function getService()
    {
        return $this->getContainer()->get('pd.scrap_page_service');
    }
}
