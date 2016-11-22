<?php

namespace AppBundle\Service\Scrapynizer;

use Doctrine\DBAL\Connection;
use ReenExe\Scrapynizer\Repository\SequenceContentRepositoryInterface;

class SequenceContentRepository implements SequenceContentRepositoryInterface
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
        $stmt = $this->connection->prepare("
            INSERT IGNORE INTO `$this->table` (`path`, `value`)
            VALUE (:path, :value);
        ");

        $stmt->execute([
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
