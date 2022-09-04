<?php

declare(strict_types=1);

namespace DataStructures\Map;

use ArrayAccess;
use ArrayIterator;
use Countable;
use DataStructures\Iterator\WrappedIterator;
use IteratorAggregate;
use OutOfBoundsException;
use Traversable;


/**
 * technically only supports: int|float|string|object as values
 * float is not advisable as it is forced to an int.
 * @template TKey as int|string
 * @template TValue
 * @implements IteratorAggregate<TKey, TValue>
 * @implements ArrayAccess<TKey, TValue>
 */
class Map implements Countable, IteratorAggregate, ArrayAccess
{
    /**
     * @param array<TKey, TValue> $array
     */
    public function __construct(protected array $array = [])
    {
    }

    /**
     * @param iterable<TKey, TValue> $iterator
     * @return Map<TKey, TValue>
     */
    public static function fromTraversable(iterable $iterator): Map
    {
        if (is_array($iterator)) {
            return new Map($iterator);
        }
        /** @var Traversable<TKey, TValue> $iterator no other options than being a traversable here. */
        return new Map(iterator_to_array($iterator));
    }

    /**
     * @return WrappedIterator<TKey, TValue>
     */
    public function getIterator(): WrappedIterator
    {
        return new WrappedIterator(new ArrayIterator($this->array));
    }

    /**
     * @param TKey $key
     * @return bool
     */
    public function has(mixed $key): bool
    {
        return isset($this->array[$key]);
    }

    /**
     * @param TKey $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->array[$offset]);
    }

    /**
     * @param TKey $offset
     * @return TValue
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->array[$offset];
    }

    /**
     * Get a value of a given key or return null.
     * @param TKey $key
     * @return TValue|null
     */
    public function getOrNull(mixed $key): mixed
    {
        return $this->array[$key] ?? null;
    }

    /**
     * Get a value of a given key or throw an exception.
     * @param TKey $key
     * @return TValue
     * @throws OutOfBoundsException when $key is not set.
     */
    public function getOrThrow(mixed $key): mixed
    {
        return $this->array[$key] ?? throw new OutOfBoundsException("Map has no key: $key");
    }

    /**
     * @param TKey $key
     * @param TValue $value
     * @return void
     */
    public function set(mixed $key, mixed $value): void
    {
        $this->array[$key] = $value;
    }

    /**
     * @param TKey $offset
     * @param TValue $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->array[$offset] = $value;
    }

    /**
     * @param TKey $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->array[$offset]);
    }

    /**
     * @param TKey $key
     * @return void
     */
    public function remove(mixed $key): void
    {
        unset($this->array[$key]);
    }

    public function count(): int
    {
        return count($this->array);
    }

    /**
     * if value can be found in map.
     * warning: slow operation O(n)
     * @param TValue $value
     * @return bool
     */
    public function hasValue(mixed $value): bool
    {
        return $this->getIterator()
            ->any(fn($itemValue) => $itemValue === $value);
    }
}
