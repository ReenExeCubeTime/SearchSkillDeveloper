<?php

namespace AppBundle\Service\Scrap\D;

use AppBundle\Service\ConnectionService;
use AppBundle\Service\Scrap\ProfileListStorageInterface;

class ProfileListStorage extends ConnectionService implements ProfileListStorageInterface
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
        $this->connection->executeQuery('
            INSERT INTO `skill_site_list_cache` (`path`, `value`)
            VALUES (:path, :value)
        ', compact('path', 'value'));
    }
}
