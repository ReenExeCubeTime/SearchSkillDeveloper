<?php

namespace AppBundle\Service\Scrap;

interface ProfileListContentStorageInterface
{
    /**
     * @return mixed
     */
    public function create();

    /**
     * @return string
     */
    public function getLast();

    /**
     * @param $path
     * @param $value
     *
     * @return mixed
     */
    public function save($path, $value);
}
