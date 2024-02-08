<?php

namespace Zeroplex\Crawler;

use GuzzleHttp\Psr7\Response;

class LinkFinder
{
    public static function fromRequest(Response $response, string $curentUrl): array
    {
        if (null === $response) {
            throw new \Exception('Response is empty');
        }

        $html = $response->getBody()->getContents();

        if (empty($html)) {
            return [];
        }
        return self::fromHtml($html, $curentUrl);
    }

    public static function fromHtml(string $html, string $currentUrl): array
    {
        //
    }
}