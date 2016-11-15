<?php

namespace AppBundle\Service\Scrapynizer\D;

use Doctrine\DBAL\Connection;

class ProfileRepository
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    private $table;

    /**
     * @var bool
     */
    private $isNeedCreate = true;

    /**
     * ProfileRepository constructor.
     * @param Connection $connection
     * @param $table
     */
    public function __construct(Connection $connection, $table)
    {
        $this->connection = $connection;
        $this->table = $table;
    }

    public function save($path, array $data)
    {
        $this->createOnce();

        $data['path'] = $path;

        $this->connection->insert($this->table, $data);
    }

    private function createOnce()
    {
        if ($this->isNeedCreate) {
            $this->create();
            $this->isNeedCreate = false;
        }
    }

    private function create()
    {
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS `{$this->table}`(
                `id` INT PRIMARY KEY AUTO_INCREMENT,
                `path` VARCHAR(255),
                `city` VARCHAR(255),
                `title` VARCHAR(255),
                `category` VARCHAR(255),
                `salary` INT(11),
                `experience_year` TINYINT(1),
                `experience_description` TEXT,
                `skills` TEXT,
                `achievement` TEXT,
                `expect` TEXT
            ) DEFAULT CHARACTER SET=utf8;
        ");
    }
}
