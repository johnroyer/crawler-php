<?php

namespace Tests\Queue;

use Zeroplex\Crawler\Queue\ArrayQueue;

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
}