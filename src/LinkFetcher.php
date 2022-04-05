<?php

namespace Zeroplex\Crawler;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\DomCrawler\Crawler;

class LinkFetcher
{
    protected $links = [];
    protected $url;
    protected $response;
    protected $crawler;

    public function __construct(string $url, Response $res)
    {
        $this->url = $url;
        $this->response = $res;
    }

    public function getLinks()
    {
        $this->crawler = new Crawler(
            $this->response->getBody()->getContents(),
            $this->url
        );

        $links = $this->crawler->filter('a')->links();

        var_dump($links);
        exit;
        return $links;
    }
}
