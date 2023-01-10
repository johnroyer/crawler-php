<?php

namespace Zeroplex\Crawler\UrlSet;

class ArraySet implements UrlSetInterface
{
    protected $set;

    public function __construct()
    {
        $this->set = [];
    }

    /**
     * @inheritDoc
     */
    public function add(string $url): void
    {
        $this->set[$url] = null;
    }

    /**
     * @inheritDoc
     */
    public function remove(string $url): void
    {
        unset($this->set[$url]);
    }

    /**
     * @inheritDoc
     */
    public function getSize(): int
    {
        return count($this->set);
    }

    /**
     * @inheritDoc
     */
    public function isExists(string $url): bool
    {
        return array_key_exists($url, $this->set);
    }

    /**
     * @inheritDoc
     */
    public function isEmpty(): bool
    {
        return count($this->set) == 0;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return array_keys($this->set);
    }
}