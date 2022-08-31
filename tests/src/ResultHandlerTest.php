<?php

namespace Tests;

use Zeroplex\Crawler\Handler\AbstractHandler;
use Zeroplex\Crawler\ResultHandler;

class ResultHandlerTest extends TestCase
{
    protected $handler;

    public function setUp(): void
    {
        $this->handler = new ResultHandler();
    }

    public function tearDown(): void
    {
        $this->handler = null;
    }

    public function testAddDomainHandler()
    {
        $domainHandler = $this->createStub(AbstractHandler::class);
        $domainHandler->method('getDomain')
            ->willReturn('example.com');

        $this->handler->addHandler($domainHandler);

        $this->assertEquals(
            1,
            count($this->handler->listDomainsHandled())
        );

        return $this->handler;
    }

    public function testAddDuplicatedDomain()
    {
        $domainHandler = $this->createStub(AbstractHandler::class);
        $domainHandler->method('getDomain')
            ->willReturn('example.com');

        $this->expectException(\Exception::class);

        $this->handler->addHandler($domainHandler);
        $this->handler->addHandler($domainHandler);  // duplicated
    }

    public function testHandlerGetter()
    {
        $domain = 'example.com';

        $domainHandler = $this->createStub(AbstractHandler::class);
        $domainHandler->method('getDomain')
            ->willReturn($domain);

        $result = $this->handler->getHandler($domain);

        $this->assertNotSame(
            false,
            $result
        );
    }

    public function testDomainlist()
    {
        $list = ['one', 'two', 'tree'];
        foreach ($list as $name) {
            $domainHandler = $this->createStub(AbstractHandler::class);
            $domainHandler->method('getDomain')
                ->willReturn($name);

            $this->handler->addHandler($domainHandler);
        }

        $output = $this->handler->listDomainsHandled();
        $this->assertEquals(
            3,
            count($output)
        );

        foreach ($list as $name) {
            $this->assertSame(true, in_array($name, $output));
        }
    }

    public function testHandlerDeletion()
    {
        $list = ['one', 'two', 'tree'];
        foreach ($list as $name) {
            $domainHandler = $this->createStub(AbstractHandler::class);
            $domainHandler->method('getDomain')
                ->willReturn($name);

            $this->handler->addHandler($domainHandler);
        }

        $this->handler->deleteHandler($domainHandler);

        $this->assertEquals(
            2,
            count($this->handler->listDomainsHandled())
        );
    }
}