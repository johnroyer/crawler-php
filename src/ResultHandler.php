<?php

namespace Zeroplex\Crawler;

use Zeroplex\Crawler\Handler\AbstractHandler;

class ResultHandler
{
    protected $handlers;

    public function __construct()
    {
        $this->handlers = [];
    }

    public function __destruct()
    {
        $this->handlers = null;
    }

    public function addHandler(AbstractHandler $handler): bool
    {
        $domain = $handler->getDomain();
        if (array_key_exists($domain, $this->handlers)) {
            throw new \Exception('Duplicated handler');
        }
        $this->handlers[$domain] = $domain;

        return true;
    }

    public function getHandler(string $domain): ?AbstractHandler
    {
        if (!array_key_exists($domain, $this->handlers)) {
            return null;
        }
        return $this->handlers[$domain];
    }

    public function deleteHandler(AbstractHandler $handler): bool
    {
        $domain = $handler->getDomain();
        if (!array_key_exists($domain, $this->handlers)) {
            return false;
        }
        unset($this->handlers[$domain]);
        return true;
    }

    public function listDomainsHandled(): array
    {
        return array_keys($this->handlers);
    }
}