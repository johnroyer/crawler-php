<?php

namespace Zeroplex\Crawler;

use GuzzleHttp\Psr7\Response;
use Symfony\Component\DomCrawler\Crawler;

class LinkFetcher
{
    protected $links = [];
    protected $response;
    protected $crawler;

    public function __construct(Response $response)
    {
        $this->response;
    }

    public function getLinks()
    {
        $this->crawler = new Crawler(
            $this->response
        );
        $links = $this->crawler->links();

        dd($links);
        return $links;
    }
}