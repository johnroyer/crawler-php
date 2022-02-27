<?php

namespace Tests\Queue;

use Zeroplex\Crawler\Queue\ArrayQueue;

class ArrayQueueTest extends \PHPUnit\Framework\TestCase
{
    protected $queue;

    protected function setUp()
    {
        parent::setUp();
        $this->queue = new ArrayQueue();
    }

    protected function tearDown()
    {
        $this->queue = null;

        parent::tearDown();
    }

}