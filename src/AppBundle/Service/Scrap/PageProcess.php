<?php

namespace AppBundle\Service\Scrap;

class PageProcess extends AbstractTableStorage
{
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
