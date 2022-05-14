<?php

namespace Johnroyer\Crawler\Handler;

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

    abstract function isFatchable(RequestInterface $request): bool;

    abstract function handler(ResponseInterface $response): void;
}
