<?php

namespace Zeroplex\Crawler\UrlSet;

interface UrlSetInterface
{
    /**
     * Add URL into set
     *
     * @param string $url
     * @return void
     */
    public function add(string $url): void;

    /**
     * Remove URL from set
     *
     * @param string $url
     * @return void
     */
    public function remove(string $url): void;

    /**
     * Get set size
     *
     * @return int
     */
    public function getSize(): int;

    /**
     * Check if URL is already in set
     *
     * @param string $url
     * @return bool true if URL exists, else if not
     */
    public function isExists(string $url): bool;

    /**
     * Check if the set is empty
     *
     * @return bool true if nothing in the set, false if else
     */
    public function isEmpty(): bool;

    /**
     * Convert set to PHP array
     *
     * @return array array contains URLs in the set
     */
    public function toArray(): array;
}
