<?php

namespace AppBundle\Command;

use AppBundle\Command\Core\QueueComand;

class CreateSkillSiteStructureCommand extends QueueComand
{
    protected $limit = 1000;

    protected function configure()
    {
        $this->setName('create:skill:site:structure');
    }

    protected function getService()
    {
        return $this->getContainer()->get('skill.structure_service');
    }
}