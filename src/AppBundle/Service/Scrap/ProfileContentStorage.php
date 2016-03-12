<?php

namespace AppBundle\Service\Scrap;

class ProfileContentStorage extends AbstractTableStorage
{
    public function create()
    {
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS `$this->table` (
                `path` VARCHAR(255) PRIMARY KEY,
                `value` MEDIUMBLOB
            );
        ");
    }

    public function save($path, $value)
    {
        $this->connection->insert($this->table, compact('path', 'value'));
    }

    public function get($path)
    {
        return $this
            ->connection
            ->fetchColumn("
                SELECT `value`
                FROM `$this->table`
                WHERE `path` = :path
            ",
                compact('path')
            );
    }
}
