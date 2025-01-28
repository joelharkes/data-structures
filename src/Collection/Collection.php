<?php

declare(strict_types=1);

namespace DataStructures\Collection;

use ArrayAccess;
use ArrayIterator;
use Countable;
use DataStructures\Enumerable;
use DataStructures\Iterator\WrappedIterator;
use IteratorAggregate;
use OutOfBoundsException;
use Traversable;

/**
 * technically only supports: int|float|string|object as values
 * float is not advisable as it is forced to an int.
 * @template TKey of array-key
 * @template TValue
 * @implements IteratorAggregate<TKey, TValue>
 * @implements ArrayAccess<TKey, TValue>
 * @implements Enumerable<TKey, TValue>
 */
class Collection implements Countable, IteratorAggregate, ArrayAccess, Enumerable
{
    /**
     * @param array<TKey, TValue> $array
     */
    final public function __construct(protected array $array = [])
    {
    }

    /**
     * @param iterable<TKey, TValue> $iterator
     * @return Collection<TKey, TValue>
     */
    public static function fromTraversable(iterable $iterator): Collection
    {
        if (is_array($iterator)) {
            return new Collection($iterator);
        }
        /** @var Traversable<TKey, TValue> $iterator no other options than being a traversable here. */
        return new Collection(iterator_to_array($iterator));
    }

    /**
     * @return WrappedIterator<TKey, TValue>
     */
    public function getIterator(): WrappedIterator
    {
        return new WrappedIterator(new ArrayIterator($this->array));
    }

    /**
     * @param callable(TValue, ?TKey): bool $predicate
     * @return bool if at least one item matches the predicate.
     */
    public function has(callable $predicate): bool
    {
        foreach ($this->array as $key => $value) {
            if ($predicate($value, $key)) {
                return true;
            }
        }
        return false;
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
     * @param TKey $offset
     * @param TValue $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            // this breaks the whole map concept (push() method to auto increment the key), maybe rename the class?
            $this->array[] = $value;
            return;
        }
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
    public function hasValue(mixed $value, bool $strict = true): bool
    {
        return in_array($value, $this->array, $strict);
    }

    public function toArray(): array
    {
        $result = [];
        foreach ($this->array as $key => $value) {
            if ($value instanceof Enumerable) {
                $result[$key] = $value->toArray();
                continue;
            }
            $result[$key] = $value;
        }
        return $result;
    }

    /**
     * @template TKeyOut of array-key
     * @param callable(TValue, ?TKey): TKey $selector
     * @param bool $preserveKeys
     * @return static<TKeyOut, static<($preserveKeys is true ? TKey : int), TValue>>
     */
    public function groupBy(callable $selector, bool $preserveKeys = false): static
    {
        $result = [];
        foreach ($this->array as $key => $value) {
            $groupKey = $selector($value, $key);
            if (!array_key_exists($groupKey, $result)) {
                $result[$groupKey] = new static();
            }
            if ($preserveKeys) {
                $result[$groupKey][$key] = $value;
            } else {
                $result[$groupKey][] = $value;
            }
        }
        return new static($result);
    }

    public function map(callable $selector): static
    {
        $result = [];

        foreach ($this->array as $key => $value) {
            $result[$key] = $selector($value, $key);
        }
        return new static($result);
    }

    public function mapKey(callable $selector): static
    {
        $result = [];
        foreach ($this->array as $key => $value) {
            $result[$selector($value, $key)] = $value;
        }
        return new static($result);
    }


    public function flatten(): Enumerable
    {
        $result = [];
        foreach ($this->array as $value) {
            if (!is_iterable($value)) {
                $result[] = $value;
                continue;
            }
            foreach ($value as $subValue) {
                $result[] = $subValue;
            }
        }
        return new static($result);
    }

    public function first(callable $predicate = null, bool $throwIfNone = false): mixed
    {
        $filter = $predicate ?? static fn () => true;
        foreach ($this->array as $key => $value) {
            if ($filter($value, $key)) {
                return $value;
            }
        }
        throw new OutOfBoundsException("No first element found");
    }

    public function hasKey(mixed $key): bool
    {
        return array_key_exists($key, $this->array);
    }

    public function isEmpty(): bool
    {
        return count($this->array) === 0;
    }

    public function filter(callable $shouldKeep): Enumerable
    {
        $result = [];
        foreach ($this->array as $key => $value) {
            if ($shouldKeep($value, $key)) {
                $result[$key] = $value;
            }
        }
        return new static($result);
    }

    public function skip(int $offset, bool $preserveKeys = true): Enumerable
    {
        return $this->slice($offset, null, $preserveKeys);
    }

    public function take(int $length, bool $preserveKeys = true, bool $throwIfLess = false): Enumerable
    {
        return $this->slice(0, $length, $preserveKeys, $throwIfLess);
    }

    public function slice(int $offset = 0, ?int $length = null, bool $preserveKeys = false, bool $throwIfLess = false): Enumerable
    {
        $result = array_slice($this->array, $offset, $length, preserve_keys: $preserveKeys);
        if ($throwIfLess && count($result) < $length) {
            throw new OutOfBoundsException("Not enough items to slice");
        }
        return new static($result);
    }

    public function skipWhile(callable $predicate, bool $throwAllSkipped = false): Enumerable
    {
        $fn = static fn ($value, $key) => !$predicate($value, $key);
        return $this->skipUntil($fn, $throwAllSkipped);
    }

    public function skipUntil(callable $predicate, bool $throwAllSkipped = false): Enumerable
    {
        $result = [];
        $skipped = false;
        foreach ($this->array as $key => $value) {
            if ($skipped || $predicate($value, $key)) {
                $result[$key] = $value;
                $skipped = true;
            }
        }
        if ($throwAllSkipped && !$skipped) {
            throw new OutOfBoundsException("All items were skipped");
        }
        return new static($result);
    }

    public function keys(): Enumerable
    {
        return new static(array_keys($this->array));
    }

    public function values(): Enumerable
    {

        return new static(array_values($this->array));
    }

    public function every(callable $predicate): bool
    {
        foreach ($this->array as $key => $value) {
            if (!$predicate($value, $key)) {
                return false;
            }
        }
        return true;
    }

    public function flip(): Enumerable
    {
        return new static(array_flip($this->array));
    }

    public function implode(string $glue): string
    {
        return implode($glue, $this->array);
    }


    public function excludeNull(): Enumerable
    {
        return $this->filter(fn ($value) => $value !== null);
    }

    public function mapToColumn(string $arrayKey): Enumerable
    {
        return $this->map(fn ($value) => $value[$arrayKey]);
    }

    public function keyByColumn(string $columnName): Enumerable
    {
        return $this->mapKey(fn ($value) => $value[$columnName]);
    }

    public function groupByColumn(string $columnName, bool $preserveKeys = false): Enumerable
    {
        return $this->groupBy(fn ($value): mixed => $value[$columnName], $preserveKeys);
    }
}
