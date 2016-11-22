<?php

namespace AppBundle\Service\Scrapynizer\Common;

use Doctrine\DBAL\Connection;

abstract class AbstractProfileRepository
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var bool
     */
    protected $isNeedCreate = true;

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

    abstract protected function create();
}
