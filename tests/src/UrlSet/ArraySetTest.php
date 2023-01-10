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

    public function testAddUrlAfterInitialized()
    {
        $this->set->add('hello');

        $this->assertEquals(
            1,
            $this->set->getSize()
        );

        return $this->set;
    }

    /**
     * @depends testAddUrlAfterInitialized
     */
    public function testAddSecondUrl($set)
    {
        $set->add('world');

        $this->assertEquals(
            2,
            $set->getSize()
        );
    }

    public function testIsExistsWithExistsValue()
    {
        $refProperty = new \ReflectionProperty($this->set, 'set');
        $refProperty->setAccessible(true);
        $refProperty->setValue($this->set, ['foo' => null]);

        $this->assertSame(
            true,
            $this->set->isExists('foo')
        );

        return $this->set;
    }

    /**
     * @depends testIsExistsWithExistsValue
     */
    public function testIsExistsWithUndefinedValue($set)
    {
        $this->assertSame(
            false,
            $set->isExists('bar')
        );
    }

    public function testRemoveExistsValue()
    {
        $this->set->add('foo');
        $this->set->add('bar');

        $this->set->remove('bar');

        $this->assertEquals(
            1,
            $this->set->getSize()
        );
    }

    public function testRemoveUnexistsValue()
    {
        $this->set->add('foo');
        $this->set->add('bar');

        $this->set->remove('not-exists');

        $this->assertEquals(
            2,
            $this->set->getSize()
        );
    }
}