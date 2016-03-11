<?php

namespace AppBundle\Service\Scrap;

use Doctrine\DBAL\Connection;

class AbstractTableStorage
{
    /**
     * @var string
     */
    protected $table;

    /**
     * @var Connection
     */
    protected $connection;

    public function __construct(Connection $connection, $table)
    {
        $this->connection = $connection;
        $this->table = $table;
    }

    public function getTable()
    {
        return $this->table;
    }
}
