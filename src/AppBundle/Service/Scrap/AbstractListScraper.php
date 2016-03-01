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
     * @var ProfileListContentStorageInterface
     */
    protected $profileListStorage;

    public function __construct(Connection $connection, ProfileListContentStorageInterface $profileListStorage)
    {
        $this->connection = $connection;
        $this->profileListStorage = $profileListStorage;
    }
}
