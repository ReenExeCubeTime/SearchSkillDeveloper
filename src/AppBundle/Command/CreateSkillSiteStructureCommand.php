<?php

namespace AppBundle\Command;

use AppBundle\Command\Core\QueueCommand;

class CreateSkillSiteStructureCommand extends QueueCommand
{
    protected $defaultLimit = 1000;

    protected $name = 'd:create:skill:site:structure';

    protected function getService()
    {
        return $this->getContainer()->get('pd.skill.structure_service');
    }
}