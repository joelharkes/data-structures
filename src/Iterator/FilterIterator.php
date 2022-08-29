<?php
declare(strict_types=1);

namespace DataStructures\Iterator;

use Closure;

/**
 * @template TKey
 * @template-covariant TValue
 * @extends WrappedIterator<TKey, TValue>
 */
class FilterIterator extends WrappedIterator
{

    public function __construct(\Iterator $iterator, private readonly Closure $filterFn)
    {
        parent::__construct($iterator);
    }

    public function next(): void
    {
        $fn = $this->filterFn;
        do {
            parent::next();
        } while ($this->valid() && !$fn($this->current()));
    }
}
