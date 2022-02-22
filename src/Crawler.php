<?php

namespace Johnroyer\Crawler;

class Crawler
{
    protected $startUrl = '';

    /**
     */
    public function __construct()
    {
        // initialize options
    }

    public function __destruct()
    {
        // TODO: save crawler status
    }

    public function crawl(string $url) {
        $this->startUrl = $url;
    }
}