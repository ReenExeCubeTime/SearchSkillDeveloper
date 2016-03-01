<?php

namespace AppBundle\Service\Scrap\D;

use AppBundle\Service\ConnectionService;
use AppBundle\Service\Scrap\ProfileListContentStorageInterface;

class ProfileListContentStorage extends ConnectionService implements ProfileListContentStorageInterface
{
    public function getLast()
    {
        return $this->connection->executeQuery('
                SELECT `value` FROM `skill_site_list_cache`
                ORDER BY `id` DESC
                LIMIT 1;
            ')
            ->fetchColumn();
    }

    public function save($path, $value)
    {
        $this->connection->insert('skill_site_list_cache', compact('path', 'value'));
    }
}
