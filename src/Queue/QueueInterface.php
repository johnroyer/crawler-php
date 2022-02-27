<?php

namespace Zeroplex\Crawler\Queue;

/**
 * This interface defined major queue behaviour should be implement.
 *
 * A Queue is a “first in, first out” or “FIFO” collection that
 * only allows access to the value at the front of the queue and iterates
 * in that order, destructively.
 *
 * Every customize queue object should implement methods defined.
 * Crawler in this project will use ONLY methods litsed in the interface.
 */
interface QueueInterface
{
    /**
     * Check if the queue is empty.
     *
     * Return true if the queue has no element inside, false if not.
     *
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * Return the size of current queue
     *
     * @return int
     */
    public function getLength(): int;

    /**
     * Push element into queue
     *
     * @param mixed $element
     * @return void
     */
    public function push(mixed $element): void;

    /**
     * Get an element from the queue
     *
     * @return mixed
     */
    public function pop(): mixed;
}