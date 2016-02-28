<?php

namespace AppBundle\Command\SkillSlte;

use AppBundle\Command\Core\QueueComand;

class AnalyzeStructureCommand extends QueueComand
{
    protected function configure()
    {
        $this->setName('skill:site:analyze:structure');
    }

    protected function getService()
    {
        return $this->getContainer()->get('skill.analyze_structure_service');
    }
}