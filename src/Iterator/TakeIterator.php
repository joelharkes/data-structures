<?php declare(strict_types=1);

/* (c) Copyright Frontify Ltd., all rights reserved. */

namespace DataStructures\Iterator;

use Iterator;

/**
 * @template TKey
 * @template-covariant TValue
 * @extends WrappedIterator<TKey, TValue>
 */
class TakeIterator extends WrappedIterator
{
    private int $count = 0;

    public function __construct(Iterator $iterator, private readonly int $take)
    {
        parent::__construct($iterator);
    }

    public function valid(): bool
    {
        return $this->count < $this->take && parent::valid();
    }

    public function next(): void
    {
        parent::next();
        $this->count++;
    }

    public function rewind(): void
    {
        parent::rewind();
        $this->count = 0;
    }
}
