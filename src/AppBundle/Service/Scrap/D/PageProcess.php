<?php

namespace AppBundle\Service\Scrap\D;

use AppBundle\Service\ConnectionService;
use AppBundle\Service\Scrap\PageProcessInterface;

class PageProcess extends ConnectionService implements PageProcessInterface
{
    public function getNextList($limit)
    {
        return $this->connection
            ->fetchAll('
                SELECT `path` FROM `skill_site_page_queue`
                WHERE `process` = 0
                LIMIT $limit;
            ');
    }

    public function exclude($path)
    {
        return $this->connection
            ->exec("
                UPDATE `skill_site_page_queue`
                SET `process` = 1
                WHERE `path` = '$path'
            ");
    }

}
