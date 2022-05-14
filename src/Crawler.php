<?php

namespace Zeroplex\Crawler;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

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

    public function setFollowRedirect()
    {
        $this->allowRedirect = true;

        return $this;
    }

    public function isFollowRedirect()
    {
        return $this->allowRedirect;
    }

    public function setTimeout(int $second)
    {
        if (1 >= $second) {
            throw new \Exception('timeout must larager then 1');
        }
        $this->timeout = $second;

        return $this;
    }

    public function getTimeout()
    {
        $this->timeout;
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

        $request = new Request('GET', $this->startUrl);
        $request->withUri(new \GuzzleHttp\Psr7\Uri($this->startUrl))
            ->withMethod('GET')
            ->withHeader(
                'User-Agent',
                $this->userAgent,
            );

        $client = new Client();
        $response = $client->send($request, [
            'allow_redirects' => $this->allowRedirect,
            'connect_timeout' => $this->timeout,
            'delay' => $this->delay,
            'read_timeout' => $this->timeout,
            'headers' => [
                'User-Agent' => $this->userAgent,
            ]
        ]);

        $crawler = new \Symfony\Component\DomCrawler\Crawler(
            $response->getBody()->getContents(),
            $url
        );
        $links = $crawler->filter('a')->links();

        return $response;
    }
}
