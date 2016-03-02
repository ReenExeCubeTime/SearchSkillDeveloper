<?php

namespace AppBundle\Service\Scrap;

use Doctrine\DBAL\Connection;

class PagePathQueue
{
    protected $table;

    protected $connection;

    public function __construct(Connection $connection, $table)
    {
        $this->connection = $connection;
        $this->table = $table;
    }

    public function create()
    {
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS `$this->table` (
                `path` VARCHAR(255) PRIMARY KEY,
                `process` TINYINT DEFAULT 0
            );
        ");
    }

    public function push(array $list)
    {
        $this->connection->beginTransaction();

        $statement = $this->connection->prepare("
            INSERT INTO `$this->table`(`path`)
            VALUES (:path)
        ");

        foreach ($list as $path) {
            $statement->execute(compact('path'));
        }

        $this->connection->commit();
    }
}
