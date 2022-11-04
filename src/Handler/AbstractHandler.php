<?php

namespace Zeroplex\Crawler\Handler;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractHandler
{
    /**
     * Return the domain name
     *
     * return the domain name. protocol and URI excluded.
     *
     * @return string
     */
    abstract public function getDomain(): string;

    /**
     * Tells if the URL should be crawled or not
     *
     * @param RequestInterface $request HTTP request
     * @return bool true if URL should be crawled, false if not
     */
    abstract function shouldFetch(RequestInterface $request): bool;

    /**
     * HTTP response handler
     *
     * find, save, or whatever you want to do with the HTTP response
     *
     * @param ResponseInterface $response
     * @return void
     */
    abstract function handler(ResponseInterface $response): void;
}
