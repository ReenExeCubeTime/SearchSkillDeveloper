<?php

namespace AppBundle\Service\Scrap;

interface ProfileListContentStorageInterface
{
    public function getLast();

    public function save($path, $value);
}
