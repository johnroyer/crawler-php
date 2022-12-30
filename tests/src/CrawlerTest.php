<?php

namespace Tests;

use GuzzleHttp\Psr7\Request;
use Zeroplex\Crawler\Crawler;
use Zeroplex\Crawler\Handler\AbstractHandler;
use Zeroplex\Crawler\Queue\ArrayQueue;

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

    public function testUserAgentSetting()
    {
        $agent = 'Firefox';

        $this->crawler->setUserAgnet($agent);

        $this->assertEquals(
            $agent,
            $this->crawler->getUserAgent()
        );
    }

    public function testUserAgentAsEmptyString()
    {
        $this->crawler->setUserAgnet('');

        // user agent can be empty string (works as "reset user agent")
        $this->assertEquals(
            '',
            $this->crawler->getUserAgent()
        );
    }

    public function testDelayTime()
    {
        $delay = 10;
        $this->crawler->setDelay($delay);

        $this->assertEquals(
            $delay,
            $this->crawler->getDelay()
        );
    }

    public function testInvalidDelayTime()
    {
        $delay = -1;

        $this->expectException(\Exception::class);
        $this->crawler->setDelay($delay);
    }

    public function testAddingHandler()
    {
        $handler = $this->createMock(AbstractHandler::class);
        $this->crawler->addHandler($handler);

        $this->assertEquals(
            1,
            count($this->crawler->getHandlers())
        );
    }

    public function testDeleteHandler()
    {
        $handler = $this->createMock(AbstractHandler::class);
        $handler->expects($this->atLeast(1))
            ->method('getDomain')
            ->willReturn('zeroplex.tw');
        $this->crawler->addHandler($handler);

        $this->crawler->deleteHandler($handler);

        $this->assertEquals(
            0,
            count($this->crawler->getHandlers())
        );
    }

    public function testHandlerGetterByDomain()
    {
        $domain = 'zeroplex.tw';
        $handler = $this->createMock(AbstractHandler::class);
        $handler->expects($this->atLeast(1))
            ->method('getDomain')
            ->willReturn($domain);
        $this->crawler->addHandler($handler);

        $result = $this->crawler->getHandlerByDomain($domain);
        $this->assertTrue($result !== null);
        $this->assertEquals(
            $domain,
            $result->getDomain()
        );
    }

    public function testDomainFetchChecker()
    {
        $domain = 'zeroplex.tw';
        $fetch = false;
        $handler = $this->createMock(AbstractHandler::class);
        $handler->expects($this->atLeast(1))
            ->method('getDomain')
            ->willReturn($domain);
        $handler->expects($this->atLeast(1))
            ->method('shouldFetch')
            ->willReturn($fetch);
        $this->crawler->addHandler($handler);

        $request = new Request('GET', 'https://' . $domain);
        $this->assertSame(
            $fetch,
            $this->crawler->shouldFetch($request)
        );
    }

    public function testQueueSettingUpWithNull()
    {
        $this->crawler->setupQueue();

        $propRef = new \ReflectionProperty($this->crawler, 'queue');
        $propRef->setAccessible(true);

        $this->assertEquals(
            'Zeroplex\Crawler\Queue\ArrayQueue',
            get_class($propRef->getValue($this->crawler))
        );
    }

    public function testQueueSettingUpWithCustomQueue()
    {
        $queue = new ArrayQueue();
        $this->crawler->setupQueue($queue);

        $propRef = new \ReflectionProperty($this->crawler, 'queue');
        $propRef->setAccessible(true);

        $this->assertEquals(
            'Zeroplex\Crawler\Queue\ArrayQueue',
            get_class($propRef->getValue($this->crawler))
        );
    }

    public function testShoudFetchWithoutHandler()
    {
        $request = new Request('GET', 'https://test.com');

        $this->assertSame(
            false,
            $this->crawler->shouldFetch($request)
        );
    }
}