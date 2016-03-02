<?php

namespace AppBundle\Service\Scrap;

interface PageProcessInterface
{
    public function getNextList($limit);

    public function exclude($path);
}
