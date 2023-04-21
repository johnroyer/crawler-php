<?php

namespace Tests\Unit;

use Zeroplex\Crawler\Url;

class UrlTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        parent::_before();
    }

    protected function _after()
    {
        parent::_after();
    }

    public function urlNormalizeProvider()
    {
        return [
            [
                'http://test.com/',
                'http://test.com:80/',
            ],
            [
                'https://test.com/',
                'https://test.com:443/',
            ],
            [
                'http://test.com/',
                'http://test.com/?',
            ],
            [
                'http://test.com/',
                'http://test.com',
            ],
            [
                'https://test.com/?name=test',
                'https://test.com/?name=test&',
            ],
            [
                'https://test.com/?a=1&b=2',
                'https://test.com/?b=2&a=1',
            ],
        ];
    }

    /**
     * @dataProvider urlNormalizeProvider
     */
    public function testUrlNormalize($expcted, $url)
    {
        $this->assertEquals(
            $expcted,
            Url::normalize($url),
        );
    }

    public function urlFragmentProvider()
    {
        return [
            [
                'https://test.com/',
                'https://test.com/#article',
            ],
            [
                'https://test.com/',
                'https://test.com/#post-comment',
            ],
            [
                'https://test.com/same/',
                'https://test.com/same/',
            ],
            [
                'https://test.com/?a=1&b=2',
                'https://test.com/?a=1&b=2',
            ],
            [
                'https://test.com/?b=1&a=1',
                'https://test.com/?b=1&a=1',
            ],
        ];
    }

    /**
     * @dataProvider urlFragmentProvider
     */
    public function testFragmentStriper($expected, $url)
    {
        $this->assertEquals(
            $expected,
            Url::stripFragment($url),
        );
    }
}
