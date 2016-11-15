<?php

namespace ReenExe\Scrapynizer\Finder;

use Symfony\Component\DomCrawler\Crawler;

class CollectionSelectorFinder implements CollectionFinderInterface
{

    /**
     * @var string
     */
    protected $selector;

    /**
     * @var callable
     */
    protected $closure;

    /**
     * @param string $selector
     * @param callable $closure
     */
    public function __construct($selector, callable $closure)
    {
        $this->selector = $selector;
        $this->closure = $closure;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(Crawler $crawler)
    {
        return $crawler
            ->filter($this->selector)
            ->each($this->closure);
    }
}
