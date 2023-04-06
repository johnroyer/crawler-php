<?php

namespace Zeroplex\Crawler\UrlQueue;

class ArrayQueue implements UrlQueueInterface
{
    protected $list;

    public function __construct()
    {
        $this->list = [];
    }

    /**
     * @inheritDoc
     */
    public function isEmpty(): bool
    {
        return (0 === count($this->list));
    }

    /**
     * @inheritDoc
     */
    public function getLength(): int
    {
        return count($this->list);
    }

    /**
     * @inheritDoc
     */
    public function push(string $url): void
    {
        $this->list[$url] = 0;
    }

    /**
     * @inheritDoc
     */
    public function pop(): string
    {
        $url = array_key_first($this->list);
        unset($this->list[$url]);
        return $url;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return array_keys($this->list);
    }

    public function isExists(string $url): bool
    {
        return array_key_exists($url, $this->list);
    }
}
