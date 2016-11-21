<?php

namespace ReenExe\Scrapynizer\Repository;

interface SequenceContentRepositoryInterface
{
    /**
     * @return string
     */
    public function getLast();

    /**
     * @param $path
     * @param $value
     * @return mixed
     */
    public function save($path, $value);
}
