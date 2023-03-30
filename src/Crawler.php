<?php

namespace Zeroplex\Crawler;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\Utils;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use URL\Normalizer;
use Zeroplex\Crawler\Handler\AbstractHandler;
use Zeroplex\Crawler\UrlQueue\ArrayQueue;
use Zeroplex\Crawler\UrlQueue\UrlQueueInterface;
use Zeroplex\Crawler\UrlSet\ArraySet;
use Zeroplex\Crawler\UrlSet\UrlSetInterface;

class Crawler
{
    protected bool $allowRedirect = false;
    protected float $timeout = 10.0;
    protected float $delay = 0.0;
    protected string $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.75 Safari/537.36';
    protected Response $response;
    protected ?ResultHandler $domainHandler;
    protected ?UrlQueueInterface $queue;
    protected ?UrlSetInterface $crawledUrl;
    protected array $guzzlePromise;
    protected int $maxCouncurrent;
    protected array $requests;

    /**
     */
    public function __construct()
    {
        $this->domainHandler = new ResultHandler();
        $this->queue = null;
        $this->crawledUrl = null;
        $this->maxCouncurrent = 1;
        $this->guzzlePromise = [];
        $this->requests = [];
    }

    public function __destruct()
    {
        $this->domainHandler = null;
    }

    /**
     * Follow HTTP redirect
     *
     * @param bool $follow true if crawler should follow redirect, false if not
     * @return $this
     */
    public function setFollowRedirect(bool $follow): Crawler
    {
        $this->allowRedirect = $follow;

        return $this;
    }

    /**
     * Check if crawler will follow HTTP redirect
     *
     * @return bool true if follow redirect, false if not
     */
    public function isFollowRedirect(): bool
    {
        return $this->allowRedirect;
    }

    /**
     * Set HTTP request timeout in seconds
     *
     * @param int $second HTTP request timeout in seconds, equals or larger then 1
     * @return $this
     * @throws Exception if input value is not valid
     */
    public function setTimeout(int $second): Crawler
    {
        if (1 > $second) {
            throw new Exception('timeout must equal or larger then 1');
        }
        $this->timeout = $second;

        return $this;
    }

    public function setupQueue(UrlQueueInterface $q = null): void
    {
        if (null !== $q) {
            $this->queue = $q;
            return;
        }
        if (null !== $this->queue) {
            return;
        }
        $this->queue = new ArrayQueue();
    }

    public function setupCrawledUrlSet(UrlSetInterface $s = null): void
    {
        if (null !== $s) {
            $this->crawledUrl = $s;
            return;
        }
        if (null !== $this->crawledUrl) {
            return;
        }
        $this->crawledUrl = new ArraySet();
    }

    /**
     * Get HTTP request timeout in seconds
     *
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * Set user agent for crawler
     *
     * @param string $agent user agent name
     * @return $this
     */
    public function setUserAgent(string $agent = ''): Crawler
    {
        $this->userAgent = $agent;
        return $this;
    }

    /**
     * Get user agent name
     *
     * @return string name of user agent
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * Time delays between HTTP requests
     *
     * @param int $second time between HTTP requests
     * @return $this
     * @throws Exception if input is not valid
     */
    public function setDelay(int $second): Crawler
    {
        if (0 > $second) {
            throw  new Exception('delay must be 0 or bigger');
        }
        $this->delay = $second;

        return $this;
    }

    /**
     * Get time delays between HTTP requests
     *
     * @return int time deploy between HTTP requests
     */
    public function getDelay(): int
    {
        return $this->delay;
    }

    public function getMaxConcurrent(): int
    {
        return $this->maxCouncurrent;
    }

    public function setMaxConcurrent(int $count): void
    {
        if (1 > $count) {
            throw new Exception('count must equal or larger then 1');
        }
        $this->maxCouncurrent = $count;
    }

    /**
     * Add handler for specific domain
     *
     * @param AbstractHandler $handler domain handler
     * @return bool true if success, false if not
     * @throws Exception if input is not valid
     */
    public function addHandler(AbstractHandler $handler): bool
    {
        return $this->domainHandler->addHandler($handler);
    }

    /**
     * Get all domain handlers
     *
     * @return array all domain handlers in array
     */
    public function getHandlers(): array
    {
        return $this->domainHandler->listDomainsHandled();
    }

    /**
     * Find domain handler by domain name
     *
     * @param string $domain
     * @return AbstractHandler|null
     */
    public function getHandlerByDomain(string $domain): ?AbstractHandler
    {
        return $this->domainHandler->getHandler($domain);
    }

    /**
     * Delete specific domain handler
     *
     * @param AbstractHandler $handler domain handler which to delete
     * @return $this
     */
    public function deleteHandler(AbstractHandler $handler): Crawler
    {
        $this->domainHandler->deleteHandler($handler);
        return $this;
    }

    /**
     * figure out if URL should be fetched
     * @param Request $request
     * @return bool
     */
    public function shouldFetch(Request $request): bool
    {
        $handler = $this->domainHandler->getHandler($request->getUri()->getHost());
        if (null === $handler) {
            return false;
        }
        return $handler->shouldFetch($request);
    }

    /**
     * Start to crawl web pages
     *
     * @param string $url Url that starts from
     */
    public function run(string $url = ''): void
    {
        $this->setupQueue();
        $this->setupCrawledUrlSet();

        if (empty($url)) {
            return;
        }
        $this->fetchAndSave($url);

        while (!$this->queue->isEmpty()) {
            $url = $this->queue->pop();
            $this->fetchAndSave($url);
        }
    }

    protected function fetchAndSave(string $url): void
    {
        $url = (new Normalizer($url))->normalize();
        if ($this->crawledUrl->isExists($url)) {
            // already fetched
            return;
        }

        $request = new Request('GET', $url);
        if (!$this->shouldFetch($request)) {
            return;
        }

        $options = [
            'allow_redirects' => $this->allowRedirect,
            'connect_timeout' => $this->timeout,
            'delay' => $this->delay,
            'http_errors' => false,
            'read_timeout' => 10.0,
            'headers' => [
                $this->userAgent,
            ],
        ];

        $key = count($this->guzzlePromise);
        $client = new Client();
        $this->guzzlePromise[$key] = $client->getAsync(
            $url,
            $options,
        )->then(function (ResponseInterface $response) use ($request, $url) {
            $this->domainHandler
                ->getHandler($request->getUri()->getHost())
                ->handle($response, $request);

            // save to crawled set
            $this->crawledUrl->add($url);

            // get links from content, and add them to queue
            $this->findAndSaveLinks($response, $url);
        });

        if (
            $this->queue->isEmpty()
            || count($this->guzzlePromise) == $this->maxCouncurrent
        ) {
            Utils::unwrap($this->guzzlePromise);

            // reset
            $this->guzzlePromise = [];
            $this->requests = [];
        }
    }

    protected function findAndSaveLinks(Response $response, string $currentUrl): void
    {
        $parsedUrls = [];

        foreach ($this->getLinks($response, $currentUrl) as $url) {
            $url = $this->normalizeUrl($url);

            if (array_key_exists($url, $parsedUrls)) {
                // duplicated URL
                continue;
            }

            if ($this->crawledUrl->isExists($url)) {
                // URL has fetched
                continue;
            }

            $request = new Request('GET', $url);
            if (!$this->shouldFetch($request)) {
                continue;
            }
            $this->queue->push($url);
            $parsedUrls[$url] = 0;
        }
    }

    protected function normalizeUrl(string $url): string
    {
        return (new Normalizer($url, true, true))
            ->normalize();
    }

    /**
     * Get pages links and assets links from HTTP response
     *
     * @param Response $response HTTP response
     * @param string $url URL of the HTTP response
     * @return array URLs in array
     */
    protected function getLinks(Response $response, string $url): array
    {
        $html = $response->getBody()->getContents();
        $result = preg_match('/meta charset=\"([^\"]+)\"/u', $html, $matches);
        if (1 !== $result) {
            $encoding = 'UTF-8';
        } else {
            $setting = strtoupper($matches[1]);
            if (in_array($setting, mb_list_encodings())) {
                $encoding = $setting;
            } else {
                $encoding = 'UTF-8';
            }
        }

        $links = [];
        $crawler = new \Symfony\Component\DomCrawler\Crawler(
            '',
            $url
        );
        $crawler->addHtmlContent($html, $encoding);

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
