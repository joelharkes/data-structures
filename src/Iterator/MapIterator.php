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
class MapIterator extends WrappedIterator
{
    /**
     * @param Iterator<TKey, TMapFrom> $iterator
     * @param Closure(TMapFrom, TKey): TValue $mapFn
     */
    public function __construct(Iterator $iterator, private readonly Closure $mapFn)
    {
        /** @phpstan-ignore-next-line even though it's not the right type we fix it by overwriting current() */
        parent::__construct($iterator);
    }

    /**
     * @return TValue
     */
    public function current(): mixed
    {
        $mapFn = $this->mapFn;
        /** @var TMapFrom $value */
        $value = parent::current();
        return $mapFn($value, parent::key());
    }
}
