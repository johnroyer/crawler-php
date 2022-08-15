<?php

namespace Tests;

use Zeroplex\Crawler\Crawler;

class CrawlerTest extends TestCase
{
    private $cawler = null;

    public function setUp(): void
    {
        parent::setUp();
        $this->cawler = new Crawler();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->cawler = null;
    }
}