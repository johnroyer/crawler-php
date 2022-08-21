<?php

namespace Zeroplex\Crawler;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Zeroplex\Crawler\Queue\ArrayQueue;

class Crawler
{
    protected $startUrl = '';
    protected $allowRedirect = false;
    protected $timeout = 10;
    protected $delay = 0;
    protected $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.75 Safari/537.36';
    protected $response;

    /**
     */
    public function __construct()
    {
    }

    public function __destruct()
    {
    }

    public function setFollowRedirect(bool $allowable): Crawler
    {
        $this->allowRedirect = $allowable;

        return $this;
    }

    public function isFollowRedirect(): bool
    {
        return $this->allowRedirect;
    }

    public function setTimeout(int $second): Crawler
    {
        if (1 > $second) {
            throw new \Exception('timeout must equal or larager then 1');
        }
        $this->timeout = $second;

        return $this;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function setUserAgnet(string $agent = '')
    {
        if (!empty($agent)) {
            $this->userAgent = $agent;
        }
    }

    public function getUserAgent()
    {
        return $this->userAgent;
    }

    public function setDelay(int $second)
    {
        if (0 > $second) {
            throw  new \Exception('delay must be 0 or bigger');
        }
        $this->delay = $second;

        return $this;
    }

    public function getDelay()
    {
        return $this->delay;
    }

    public function run(string $url)
    {
        $this->startUrl = $url;
        $this->queue = new ArrayQueue();

        $this->response = $this->fetch($url);
        foreach ($this->getLinks($this->response, $url) as $url) {
            $this->queue->push($url);
        }

        return $this->response->getBody()->getContents();
    }

    protected function fetch(string $url): Response
    {
        $request = new Request(
            'GET',
            $url
        );
        $request->withHeader(
            'User-Agent',
            $this->userAgent
        );

        $client = new Client();
        $this->response = $client->send(
            $request,
            [
                'allow_redirects' => $this->allowRedirect,
                'connect_timeout' => $this->timeout,
                'delay' => $this->delay,
                'read_timeout' => $this->timeout
            ]
        );

        return $this->response;
    }

    protected function getLinks(Response $response, string $url): array
    {
        $html = $response->getBody()->getContents();
        $result = preg_match('/meta charset=\"([^\"]+)\"/u', $html, $matchs);
        if (false === $result) {
            $endoing = 'UTF-8';
        } else {
            $setting = strtoupper($matchs[1]);
            if (in_array($setting, mb_list_encodings())) {
                $endoing = $setting;
            } else {
                $endoing = 'UTF-8';
            }
        }

        $links = [];
        $crawler = new \Symfony\Component\DomCrawler\Crawler(
            '',
            $url
        );
        $crawler->addHtmlContent($html, $endoing);

        $urls = $crawler->filter('a')->links();
        foreach ($urls as $url) {
            $links[] = $url->getUri();
        }

        $refs = $crawler->filter('link')->links();
        foreach ($refs as $ref) {
            $links[] = $ref->getUri();
        }

        $urls = $crawler->filter('img')->extract(['src']);
        foreach ($urls as $url) {
            if (1 !== preg_match('/^data:image/', $url)) {
                $links[] = $url;
            }
        }

        return $links;
    }
}
