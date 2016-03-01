<?php

namespace AppBundle\Service\Scrap;

interface ProfileListStorageInterface
{
    public function getLast();

    public function save($path, $value);
}
