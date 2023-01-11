<?php

namespace Tests;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Zeroplex\Crawler\Crawler;
use Zeroplex\Crawler\Handler\AbstractHandler;
use Zeroplex\Crawler\UrlQueue\ArrayQueue;

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
            'Zeroplex\Crawler\UrlQueue\ArrayQueue',
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
            'Zeroplex\Crawler\UrlQueue\ArrayQueue',
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

    public function testShouldFetchWithoutMatchedHandler()
    {
        $handler = $this->createMock(AbstractHandler::class);
        $handler->expects($this->atLeast(1))
            ->method('getDomain')
            ->willReturn('not.match');
        $this->crawler->addHandler($handler);

        $request = new Request('GET', 'https://test.com');
        $this->assertSame(
            false,
            $this->crawler->shouldFetch($request)
        );
    }

    public function testShoudFetchWithMatchedHandler()
    {
        $handler = $this->createMock(AbstractHandler::class);
        $handler->expects($this->atLeast(1))
            ->method('getDomain')
            ->willReturn('test.com');
        $handler->expects($this->once())
            ->method('shouldFetch')
            ->willReturn(false);
        $this->crawler->addHandler($handler);

        $request = new Request('GET', 'https://test.com');
        $this->assertSame(
            false,
            $this->crawler->shouldFetch($request)
        );
    }

    public function testGettingLinksWithEmptyContent()
    {
        $response = new Response(200, [], '<html></html>');
        $getlinks = new \ReflectionMethod($this->crawler, 'getLinks');
        $getlinks->setAccessible(true);

        $this->assertEquals(
            0,
            count($getlinks->invoke($this->crawler, $response, 'https://test.com'))
        );
    }

    public function testGettingLinksWithTestPage()
    {
        $html = file_get_contents(__DIR__ . '/../dataset/example.html');
        $response = new Response(
            200,
            [],
            $html
        );
        $getLinks = new \ReflectionMethod($this->crawler, 'getLinks');
        $getLinks->setAccessible(true);

        $this->assertEquals(
            4,
            count($getLinks->invoke($this->crawler, $response, 'https://test.com'))
        );
    }

    public function testCrawlerRunWithEmptyUrl()
    {
        $crawler = $this->getMockBuilder(Crawler::class)
            ->onlyMethods(['setupQueue', 'fetchAndSave'])
            ->getMock();
        $crawler->expects($this->once())
            ->method('setupQueue');
        $crawler->expects($this->never())
            ->method('fetchAndSave');

        $crawler->run();
    }

    public function testCrawlerRunWithStartUrl()
    {
        $queue = $this->createMock(ArrayQueue::class);
        $queue->expects($this->once())
            ->method('isEmpty')
            ->willReturn(true);

        $crawler = $this->getMockBuilder(Crawler::class)
            ->onlyMethods(['setupQueue', 'fetchAndSave'])
            ->getMock();
        $crawler->expects($this->once())
            ->method('setupQueue');
        $crawler->expects($this->once())
            ->method('fetchAndSave');

        $refProperty = new \ReflectionProperty($crawler, 'queue');
        $refProperty->setAccessible(true);
        $refProperty->setValue($crawler, $queue);

        $crawler->run('https://test.com');
    }
}