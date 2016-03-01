<?php

namespace AppBundle\Service\Scrap\D;

use AppBundle\Service\ConnectionService;
use AppBundle\Service\Scrap\ProfileContentStorageInterface;

class ProfileContentStorage extends ConnectionService implements ProfileContentStorageInterface
{
    public function save($path, $value)
    {
        $this->connection->insert('skill_site_page_cache', compact('path', 'value'));
    }
}
