<?php

namespace Tests\Unit;

use Codeception\Stub;
use Codeception\Test\Unit;
use ReflectionProperty;
use Zeroplex\Crawler\Handler\AbstractHandler;
use Zeroplex\Crawler\ResultHandler;

class ResultHandlerTest extends Unit
{
    protected ?ResultHandler $hander;

    protected function _before()
    {
        parent::_before();
        $this->hander = new ResultHandler();
    }

    // tests
    public function testSomeFeature()
    {
        $this->hander = null;
        parent::_after();
    }

    public function testAddHandler()
    {
        $config = Stub::make(AbstractHandler::class, [
            'getDomain' => 'test.com',
        ]);
        $this->hander->addHandler($config);

        $refProperty = new ReflectionProperty($this->hander, 'handlers');
        $refProperty->setAccessible(true);

        $this->assertEquals(
            1,
            count($refProperty->getValue($this->hander)),
        );

        return $this->hander;
    }

    /**
     * @depends testAddHandler
     */
    public function testGetDomainList($handler)
    {
        $list = $handler->listDomainsHandled();

        $this->assertEquals(
            1,
            count($list),
        );
        $this->assertSame(
            true,
            in_array('test.com', $list),
        );

        return $handler;
    }

    /**
     * @depends testGetDomainList
     */
    public function testGetHandler($handler)
    {
        $this->assertEquals(
            'test.com',
            $handler->getHandler('test.com')->getDomain(),
        );
    }

    /**
     * @depends testGetDomainList
     */
    public function testDeleteNonExistsDomainHandler($handler)
    {
        $another = $this->createMock(AbstractHandler::class);
        $another->method('getDomain')
            ->willReturn('not.exists');

        $handler->deleteHandler($another);
        $list = $handler->listDomainsHandled();
        $this->assertSame(
            1,
            count($list),
        );

        return $handler;
    }

    /**
     * @depends testDeleteNonExistsDomainHandler
     */
    public function testDeleteDomainHandler($handler)
    {
        $config = $this->createMock(AbstractHandler::class);
        $config->method('getDomain')
            ->willReturn('test.com');

        $handler->deleteHandler($config);
        $list = $handler->listDomainsHandled();
        $this->assertSame(
            0,
            count($list),
        );
    }
}
