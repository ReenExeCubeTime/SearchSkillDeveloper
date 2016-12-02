<?php

namespace AppBundle\Service\Scrapynizer\W;

use AppBundle\Service\Scrapynizer\Common\AbstractProfileRepository;

class ProfileRepository extends AbstractProfileRepository
{
    protected function create()
    {
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS `{$this->table}`(
                `id` INT PRIMARY KEY AUTO_INCREMENT,
                `path` VARCHAR(255),
                `city` VARCHAR(255),
                `title` VARCHAR(255),
                `description` TEXT
            ) DEFAULT CHARACTER SET=utf8;
        ");
    }
}
