<?php

namespace AppBundle\Service\Scrap;

use Doctrine\DBAL\Connection;

abstract class AbstractListScraper extends Scraper
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var ProfileListStorageInterface
     */
    protected $profileListStorage;

    public function __construct(Connection $connection, ProfileListStorageInterface $profileListStorage)
    {
        $this->connection = $connection;
        $this->profileListStorage = $profileListStorage;
    }
}
