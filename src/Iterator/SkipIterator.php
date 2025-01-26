<?php

declare(strict_types=1);

/* (c) Copyright Frontify Ltd., all rights reserved. */

namespace DataStructures\Iterator;

use Iterator;

/**
 * @template TKey
 * @template-covariant TValue
 * @extends WrappedIterator<TKey, TValue>
 */
class SkipIterator extends WrappedIterator
{
    private int $count = 0;

    public function __construct(Iterator $iterator, private readonly int $skip)
    {
        parent::__construct($iterator);
    }

    public function current(): mixed
    {
        $this->ensureSkipped();
        return parent::current();
    }

    public function valid(): bool
    {
        $this->ensureSkipped();

        return parent::valid();
    }

    public function next(): void
    {
        $this->ensureSkipped();
        parent::next();
    }

    public function rewind(): void
    {
        parent::rewind();
        $this->count = 0;
    }

    /**
     * @return void
     */
    public function ensureSkipped(): void
    {
        while ($this->count < $this->skip && parent::valid()) {
            parent::next();
            $this->count++;
        }
    }
}
