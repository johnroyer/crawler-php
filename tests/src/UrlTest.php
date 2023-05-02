<?php

namespace Tests;

use Zeroplex\Crawler\Url;

class UrlTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function urlNormalizeProvider()
    {
        return [
            ['http://test.com/', 'http://test.com:80'],
            ['http://test.com/path', 'http://test.com/path'],
            ['http://test.com/path/', 'http://test.com/path/'],
            ['http://test.com/?a=1&b=2', 'http://test.com/?a=1&b=2'],
            ['http://test.com/?a=1&b=2', 'http://test.com/?b=2&a=1'],
            ['http://test.com/path/?a=1&b=2', 'http://test.com/path/?b=2&a=1'],
        ];
    }

    /**
     * @dataProvider urlNormalizeProvider
     */
    public function testUrlNormalize($expected, $input)
    {
        $this->assertEquals(
            $expected,
            Url::normalize($input),
        );
    }
}