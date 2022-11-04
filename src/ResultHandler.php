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

    /**
     * Add domain handler
     *
     * @param AbstractHandler $handler
     * @return bool
     * @throws \Exception if there are duplicated domain handler
     */
    public function addHandler(AbstractHandler $handler): bool
    {
        $domain = $handler->getDomain();
        if (array_key_exists($domain, $this->handlers)) {
            throw new \Exception('Duplicated handler');
        }
        $this->handlers[$domain] = $domain;

        return true;
    }

    /**
     * Get handler by domain name
     *
     * @param string $domain domain name of the handler you want to get
     * @return AbstractHandler|null null if handler does not exist
     */
    public function getHandler(string $domain): ?AbstractHandler
    {
        if (!array_key_exists($domain, $this->handlers)) {
            return null;
        }
        return $this->handlers[$domain];
    }

    /**
     * Delete handler
     *
     * @param AbstractHandler $handler handler you want to delete
     * @return bool
     */
    public function deleteHandler(AbstractHandler $handler): bool
    {
        $domain = $handler->getDomain();
        if (!array_key_exists($domain, $this->handlers)) {
            return false;
        }
        unset($this->handlers[$domain]);
        return true;
    }

    /**
     * List all domain handler
     *
     * @return array handler in array
     */
    public function listDomainsHandled(): array
    {
        return array_keys($this->handlers);
    }
}