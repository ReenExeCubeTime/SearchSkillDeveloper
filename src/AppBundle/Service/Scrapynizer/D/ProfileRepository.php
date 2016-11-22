<?php

namespace AppBundle\Service\Scrapynizer\D;

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
