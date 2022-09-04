<?php

declare(strict_types=1);

namespace DataStructures\Iterator;

use Closure;
use DataStructures\Map\Map;
use DataStructures\Set\Set;
use DataStructures\Stack\Stack;

/**
 * @template TKey
 * @template-covariant TValue
 */
trait IteratorExtensions
{
    /**
     * @param Closure(TValue, TKey=): bool $filterFn
     * @return FilterIterator<TKey, TValue>
     */
    public function filter(Closure $filterFn): WrappedIterator
    {
        return new FilterIterator($this, $filterFn);
    }

    /**
     * @template TMappedValue
     * @param Closure(TValue, TKey=): TMappedValue $mapFn
     * @return MapIterator<TKey, TMappedValue, TValue>
     * @phpstan-ignore-next-line we know its covariant because we only call the function on this iterator it's content
     */
    public function map(Closure $mapFn): WrappedIterator
    {
        /** @var MapIterator<TKey, TMappedValue, TValue> $iterator */
        $iterator = new MapIterator($this, $mapFn);
        return $iterator;
    }

    /**
     * @template TMappedValue
     * @param Closure(TValue, TKey=): TMappedValue $mapFn
     * @return MapKeyIterator<TKey, TMappedValue, TValue>
     * @phpstan-ignore-next-line we know its covariant because we only call the function on this iterator it's content
     */
    public function mapKey(Closure $mapFn): WrappedIterator
    {
        /** @var MapKeyIterator<TKey, TMappedValue, TValue> $iterator */
        $iterator = new MapKeyIterator($this, $mapFn);
        return $iterator;
    }

    /**
     * @return Set<TValue>
     * @phpstan-ignore-next-line we know its covariant because we only call the function on this iterator it's content
     */
    public function toSet(): Set
    {
        return Set::fromTraversable($this);
    }

    /**
     * @return Stack<TValue>
     * @phpstan-ignore-next-line we know its covariant because we only call the function on this iterator it's content
     */
    public function toStack(): Stack
    {
        return Stack::fromTraversable($this);
    }

    /**
     * @return Map<TKey, TValue>
     * @phpstan-ignore-next-line only string|int iterator keys allowed but no other way to define this.
     */
    public function toMap(): Map
    {
        /** @phpstan-ignore-next-line only string|int iterator keys allowed but no other way to define this. */
        return Map::fromTraversable($this);
    }

    /**
     * @return array<TKey, TValue>
     */
    public function toArray(): array
    {
        /** @phpstan-ignore-next-line only string|int iterator keys allowed but no other way to define this. */
        return iterator_to_array($this);
    }

    /**
     * @template TOutput
     * @param TOutput $initialValue
     * @param Closure(TOutput, TValue, TKey=): TOutput $reduceFn
     * @return TOutput
     */
    public function reduce(mixed $initialValue, Closure $reduceFn): mixed
    {
        $value = $initialValue;
        foreach ($this as $key => $value) {
            $value = $reduceFn($initialValue, $value, $key);
        }
        return $value;
    }

    public function count(): int
    {
        $length = 0;
        foreach ($this as $_) {
            $length++;
        }
        return $length;
    }

    /**
     * @param string $glue
     * @return string
     */
    public function join(string $glue): string
    {
        $result = '';
        $isFirst = true;
        foreach ($this as $value) {
            if (!$isFirst) {
                $result .= $glue;
            }
            $isFirst = false;
            $result .= (string)$value;
        }
        return $result;
    }

    /**
     * @param callable(TValue, TKey=): bool $filterFn
     * @return bool
     */
    public function all(callable $filterFn): bool
    {
        foreach ($this as $key => $value) {
            if (!$filterFn($value, $key)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param callable(TValue, TKey=): bool $filterFn
     * @return bool
     */
    public function any(callable $filterFn): bool
    {
        foreach ($this as $key => $value) {
            if ($filterFn($value, $key)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param callable(TValue, TKey=): bool $filterFn
     * @return TValue|null
     */
    public function first(callable $filterFn): mixed
    {
        foreach ($this as $key => $value) {
            if ($filterFn($value, $key)) {
                return $value;
            }
        }
        return null;
    }
}
