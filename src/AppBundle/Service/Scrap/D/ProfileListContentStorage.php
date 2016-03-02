<?php

namespace AppBundle\Service\Scrap\D;

use AppBundle\Service\ConnectionService;
use AppBundle\Service\Scrap\ProfileListContentStorageInterface;

class ProfileListContentStorage extends ConnectionService implements ProfileListContentStorageInterface
{
    public function create()
    {
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS `skill_site_list_cache` (
                `id` INT(11) PRIMARY KEY AUTO_INCREMENT,
                `path` VARCHAR(255),
                `value` MEDIUMBLOB,
                UNIQUE KEY (`path`)
            );
        ");
    }

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
