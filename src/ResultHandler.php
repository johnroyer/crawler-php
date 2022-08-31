<?php

namespace Zeroplex\Crawler;

class ResultHandler
{
    protected $handlers = [];
    protected $domains = [];

    public function __construct()
    {
    }

    public function __destruct()
    {
        $this->handlers = null;
        $this->domains = null;
    }
}