<?php

namespace AppBundle\Service\Scrapynizer;

use Doctrine\DBAL\Connection;
use ReenExe\Scrapynizer\Repository\PathCollectionRepositoryInterface;

class PathProcessRepository implements QueueRepositoryInterface, PathCollectionRepositoryInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    private $table;

    public function __construct(Connection $connection, $table)
    {
        $this->connection = $connection;
        $this->table = $table;
    }

    public function push(array $list)
    {
        $this->create();

        $this->connection->beginTransaction();

        $statement = $this->connection->prepare("
            INSERT INTO `$this->table`(`path`)
            VALUES (:path)
        ");

        foreach (array_unique($list) as $path) {
            $statement->execute(compact('path'));
        }

        $this->connection->commit();
    }

    public function getNext($limit)
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

    private function create()
    {
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS `$this->table` (
                `path` VARCHAR(255) PRIMARY KEY,
                `process` TINYINT DEFAULT 0
            );
        ");
    }
}
