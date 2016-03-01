<?php

namespace AppBundle\Command;

use AppBundle\Command\Core\QueueComand;

class ScrapSkillSitePageCommand extends QueueComand
{
    protected function configure()
    {
        $this->setName('d:scrap:skill:site:page');
    }

    protected function getService()
    {
        return $this->getContainer()->get('pd.scrap_page_service');
    }
}