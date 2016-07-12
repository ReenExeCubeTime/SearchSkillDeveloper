<?php

namespace AppBundle\Service\Scrap;

class ProfileListContentStorage extends AbstractTableStorage implements ProfileListContentStorageInterface
{
    public function create()
    {
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS `$this->table` (
                `id` INT(11) PRIMARY KEY AUTO_INCREMENT,
                `path` VARCHAR(255),
                `value` MEDIUMBLOB,
                UNIQUE KEY (`path`)
            );
        ");
    }

    public function getLast()
    {
        return $this->connection->executeQuery("
                SELECT `value` FROM `$this->table`
                ORDER BY `id` DESC
                LIMIT 1;
            ")
            ->fetchColumn();
    }

    public function save($path, $value)
    {
        $this->connection->insert($this->table, compact('path', 'value'));
    }
}
