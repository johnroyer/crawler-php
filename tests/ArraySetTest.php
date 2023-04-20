<?php

namespace Tests;

use Zeroplex\Crawler\UrlSet\ArraySet;

class ArraySetTest extends \Codeception\Test\Unit
{
    protected ?ArraySet $set;
    protected function _before()
    {
        parent::_before();
        $this->set = new ArraySet();
    }

    protected function _after()
    {
        $this->set = null;
        parent::_after();
    }

    public function testIsEmptyAfterInitialized()
    {
        $this->assertSame(
            true,
            $this->set->isEmpty(),
        );
    }

    public function testAddItem()
    {
        $this->set->add('a');

        $this->assertSame(
            false,
            $this->set->isEmpty(),
        );

        return $this->set;
    }

    public function testCountItems()
    {
        $this->set->add('a');

        $this->assertSame(
            1,
            $this->set->getSize(),
        );
    }

    /**
     * @depends testAddItem
     */
    public function testRemoveItem(ArraySet $set)
    {
        $set->remove('a');

        $this->assertSame(
            0,
            $set->getSize(),
        );
    }

    public function testItemExists()
    {
        $this->set->add('a');

        $this->assertSame(
            true,
            $this->set->isExists('a'),
        );
        $this->assertSame(
            false,
            $this->set->isExists('b'),
        );
    }

    public function testToArray()
    {
        $this->set->add('a');
        $this->set->add('b');

        $out = $this->set->toArray();

        $this->assertSame(
            2,
            count($out),
        );
        $this->assertSame(
            true,
            in_array('a', $out),
        );
        $this->assertSame(
            true,
            in_array('b', $out),
        );
    }
}
