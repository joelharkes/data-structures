<?php
declare(strict_types=1);

namespace DataStructures\Iterator;

use Iterator;

/**
 * @template TKey
 * @template-covariant TValue
 * @template-implements Iterator<TKey, TValue>
 */
class WrappedIterator implements Iterator
{
    /** @use IteratorExtensions<TKey, TValue> */
    use IteratorExtensions;

    /**
     * @param Iterator<TKey, TValue> $iterator
     */
    public function __construct(private readonly Iterator $iterator)
    {
    }

    /**
     * @return TValue
     */
    public function current(): mixed
    {
        return $this->iterator->current();
    }

    public function next(): void
    {
        $this->iterator->next();
    }

    /**
     * @return TKey
     */
    public function key(): mixed
    {
        return $this->iterator->key();
    }

    public function valid(): bool
    {
        return $this->iterator->valid();
    }

    public function rewind(): void
    {
        $this->iterator->rewind();
    }
}
