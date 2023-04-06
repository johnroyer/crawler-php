<?php

namespace Tests\UrlQueue;

use Zeroplex\Crawler\UrlQueue\ArrayQueue;

class ArrayQueueTest extends \PHPUnit\Framework\TestCase
{
    protected $queue;

    protected function setUp(): void
    {
        parent::setUp();
        $this->queue = new ArrayQueue();
    }

    protected function tearDown(): void
    {
        $this->queue = null;

        parent::tearDown();
    }

    public function testQueueSize()
    {
        $this->queue->push(1);
        $this->queue->push(2);
        $this->queue->push(3);

        $this->assertEquals(
            3,
            $this->queue->getLength()
        );

        return $this->queue;
    }

    /**
     * @depends testQueueSize
     */
    public function testPopOneItem($queue)
    {
        $this->assertEquals(
            1,
            $queue->pop()
        );

        return $queue;
    }

    /**
     * @depends testPopOneItem
     */
    public function testPopSecondItem($queue)
    {
        $this->assertEquals(
            2,
            $queue->pop(),
        );
    }

    public function testToArrayOutputType()
    {
        $queue = new ArrayQueue();

        $queue->push(1);
        $queue->push(2);
        $queue->push(3);

        $output = $queue->toArray();

        $this->assertIsArray($output);

        return $output;
    }

    /**
     * @depends testToArrayOutputType
     */
    public function testToArrayContent($output)
    {
        $this->assertCount(3, $output);
    }

    public function testIsEmptyAfterInitialized()
    {
        $this->assertSame(
            true,
            $this->queue->isEmpty()
        );
    }

    public function testIsEmptyAfterAddingUrl()
    {
        $this->queue->push('https://test.com');

        $this->assertSame(
            false,
            $this->queue->isEmpty()
        );
    }

    public function testIsExists()
    {
        $url = 'https://test.com';
        $this->queue->push($url);

        $this->assertSame(
            true,
            $this->queue->isExists($url)
        );
    }
}
