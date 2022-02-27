<?php

namespace Zeroplex\Crawler\Queue;

class Queue implements QueueInterface
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
    public function push(mixed $element): void
    {
        array_push($this->list, $element);
    }

    /**
     * @inheritDoc
     */
    public function pop(): mixed
    {
        return array_pop($this->list);
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

        return $val;
    }
}