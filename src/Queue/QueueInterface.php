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
}