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
        $this->list[] = $url;
    }

    /**
     * @inheritDoc
     */
    public function pop(): string
    {
        return array_shift($this->list);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        // avoid array_merge() because performance issue;
        // @see: https://stackoverflow.com/a/23348715/8681141
        $output = [];

        foreach ($this->list as $val) {
            $output[] = $val;
        }

        return $output;
    }
}
