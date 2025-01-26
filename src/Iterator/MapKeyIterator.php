<?php

declare(strict_types=1);

namespace DataStructures\Iterator;

use Closure;
use Iterator;

/**
 * @template TKey
 * @template-covariant TValue
 * @template TMapFrom
 * @extends WrappedIterator<TKey, TValue>
 */
class MapKeyIterator extends WrappedIterator
{
    /**
     * @param Iterator<TMapFrom, TValue> $iterator
     * @param Closure(TValue, TMapFrom): TKey $mapFn
     */
    public function __construct(Iterator $iterator, private readonly Closure $mapFn)
    {
        /** @phpstan-ignore-next-line even though it's not the right type we fix it by overwriting current() */
        parent::__construct($iterator);
    }

    /**
     * @return TKey
     */
    public function key(): mixed
    {
        $mapFn = $this->mapFn;
        /** @var TMapFrom $key */
        $key = parent::key();
        return $mapFn(parent::current(), $key);
    }
}
