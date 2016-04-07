<?php

namespace AppBundle\Service\Scrap;

class PagePathProcess extends AbstractTableStorage implements PagePathQueueInterface, PageProcessInterface
{
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
        $statement = $this->connection->prepare("
            INSERT INTO `$this->table`(`path`)
            VALUES (:path)
        ");

        $this->connection->beginTransaction();
        foreach ($list as $path) {
            $statement->execute(compact('path'));
        }
        $this->connection->commit();
    }

    public function getNextList($limit)
    {
        return $this->connection
            ->executeQuery("
                SELECT `path` FROM `$this->table`
                WHERE `process` = 0
                LIMIT $limit;
            ")
            ->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function exclude($path)
    {
        return $this->connection
            ->exec("
                UPDATE `$this->table`
                SET `process` = 1
                WHERE `path` = '$path'
            ");
    }
}