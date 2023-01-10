<?php

namespace Tests\UrlSet;

use Zeroplex\Crawler\UrlSet\ArraySet;

class ArraySetTest extends \Tests\TestCase
{
    private $set;

    public function setUp(): void
    {
        parent::setUp();
        $this->set = new ArraySet();
    }

    public function tearDown(): void
    {
        $this->set = null;
        parent::tearDown();
    }

    public function testGetSizeAfterInitialized()
    {
        // should be empty after initialized
        $this->assertEquals(
            0,
            $this->set->getSize()
        );
    }

    public function testGetSizeWithDataInside()
    {
        $refPropery = new \ReflectionProperty($this->set, 'set');
        $refPropery->setAccessible(true);
        $refPropery->setValue($this->set, [1, 2, 3]);

        $this->assertEquals(
            3,
            $this->set->getSize()
        );
    }
}