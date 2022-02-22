<?php

namespace Johnroyer\Crawler;

use GuzzleHttp\Client;

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

    public function crawl(string $url)
    {
        $this->startUrl = $url;

        $httpClient = new Client();
        $content = $httpClient->get($this->startUrl);

        return true;
    }
}
