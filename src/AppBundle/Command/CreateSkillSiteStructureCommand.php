<?php

namespace AppBundle\Command;

use AppBundle\Command\Core\QueueComand;

class CreateSkillSiteStructureCommand extends QueueComand
{
    protected $defaultLimit = 1000;

    protected function configure()
    {
        $this->setName('d:create:skill:site:structure');
    }

    protected function getService()
    {
        return $this->getContainer()->get('skill.structure_service');
    }
}