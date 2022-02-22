<?php

namespace Johnroyer\Crawler;

use GuzzleHttp\Client;

class Crawler
{
    protected $startUrl = '';
    protected $allowRedirect = false;
    protected $timeout = 10;
    protected $delay = 0;
    protected $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.75 Safari/537.36';

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
