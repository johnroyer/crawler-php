<?php

namespace Zeroplex\Crawler;

use Zeroplex\Crawler\Handler\AbstractHandler;

class ResultHandler
{
    protected $handlers = [];

    public function __construct()
    {
    }

    public function __destruct()
    {
        $this->handlers = null;
    }

    public function addHandler(AbstractHandler $handler): ResultHandler
    {
        $domain = filter_var($handler->getDomain(), FILTER_VALIDATE_DOMAIN);
        if (false === $domain) {
            throw new \Exception('Handler does not have a valid domain');
        }
        if (array_key_exists($domain, $this->handlers)) {
            throw new \Exception('Duplicated handler');
        }
        $this->handlers[$domain] = $domain;

        return $this;
    }
}