<?php

namespace Tests;

use Zeroplex\Crawler\Crawler;

class CrawlerTest extends TestCase
{
    private $crawler = null;

    public function setUp(): void
    {
        parent::setUp();
        $this->crawler = new Crawler();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->crawler = null;
    }

    public function testAllowRedirect()
    {
        $this->crawler->setFollowRedirect(true);

        $this->assertTrue($this->crawler->isFollowRedirect());
    }

    public function testNotAllowRedirect()
    {
        $this->crawler->setFollowRedirect(false);

        $this->assertFalse($this->crawler->isFollowRedirect());
    }

    public function testSetTimeoutSecond()
    {
        $timeoutSeconds = 10;

        $this->crawler->setTimeout($timeoutSeconds);

        $this->assertEquals(
            $timeoutSeconds,
            $this->crawler->getTimeout()
        );
    }

    public function testTimeoutLessThenOne()
    {
        $timeoutSeconds = 0;

        $this->expectException(\Exception::class);

        $this->crawler->setTimeout($timeoutSeconds);
    }
}