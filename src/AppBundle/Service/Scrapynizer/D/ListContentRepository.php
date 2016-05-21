<?php

namespace AppBundle\Service\Scrapynizer\D;

use ReenExe\Scrapynizer\Repository\ListContentRepositoryInterface;
use Doctrine\DBAL\Connection;

class ListContentRepository implements ListContentRepositoryInterface
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

    public function getLast()
    {
        $this->create();

        return $this->connection->executeQuery("
                SELECT `value` FROM `$this->table`
                ORDER BY `id` DESC
                LIMIT 1;
            ")
            ->fetchColumn();
    }

    public function save($path, $value)
    {
        $this->connection->insert($this->table, [
            'path' => $path,
            'value' => $value,
        ]);
    }

    private function create()
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
}
