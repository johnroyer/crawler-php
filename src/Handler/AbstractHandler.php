<?php

namespace Johnroyer\Crawler\Handler;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractHandler
{
    protected string $domain;

    public function getDomain(): string
    {
        return $this->domain;
    }

    abstract function isFatchable(RequestInterface $request): bool;

    abstract function handle(ResponseInterface $response): void;
}
