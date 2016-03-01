<?php

namespace AppBundle\Service\Scrap;

interface ProfileContentStorageInterface
{
    public function save($path, $value);
}
