<?php

namespace AppBundle\Command\SkillSlte;

use AppBundle\Command\Core\QueueCommand;

class AnalyzeStructureCommand extends QueueCommand
{
    protected $name = 'skill:site:analyze:structure';

    protected function getService()
    {
        return $this->getContainer()->get('skill.analyze_structure_service');
    }
}