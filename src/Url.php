<?php

namespace Zeroplex\Crawler;

use \Zeroplex\Url\Normalizer;

class Url
{
    public static function normalize(string $url): string
    {
        return (new Normalizer($url, true, true))
            ->normalize();
    }

    public static function stripFragment(string $url): string
    {
        // remove '#' in tail
        $position = strpos($url, '#');
        if (false !== $position && 0 <= $position) {
            $url = substr($url, 0, $position);
        }
        return $url;
    }
}
