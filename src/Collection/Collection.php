<?php

declare(strict_types=1);

namespace DataStructures\Collection;

use ArrayAccess;
use ArrayIterator;
use Closure;
use Countable;
use DataStructures\Enumerable;
use DataStructures\Iterator\WrappedIterator;
use DataStructures\String\Str;
use IteratorAggregate;
use JetBrains\PhpStorm\Pure;
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
class Collection implements Countable, IteratorAggregate, ArrayAccess, Enumerable, \JsonSerializable
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
    #[Pure]
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
    #[Pure]
    public function getIterator(): WrappedIterator
    {
        return new WrappedIterator(new ArrayIterator($this->array));
    }

    /**
     * @param Closure(TValue, ?TKey): bool $predicate
     * @return bool if at least one item matches the predicate.
     */
    #[Pure]
    public function has(Closure $predicate): bool
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
    #[Pure]
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->array[$offset]);
    }

    /**
     * @param TKey $offset
     * @return TValue
     */
    #[Pure]
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

    #[Pure]
    public function count(): int
    {
        return count($this->array);
    }

    /**
     * Check array has value.
     * @param TValue $value
     * @return bool
     */
    #[Pure]
    public function hasValue(mixed $value, bool $strict = true): bool
    {
        return in_array($value, $this->array, $strict);
    }

    #[Pure]
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
     * @param Closure(TValue, ?TKey): TKeyOut $selector
     * @return static<TKeyOut, static<TKey, TValue>>
     */
    public function groupBy(Closure $selector): static
    {
        $result = [];
        foreach ($this->array as $key => $value) {
            $groupKey = $selector($value, $key);
            if (!array_key_exists($groupKey, $result)) {
                $result[$groupKey] = new static();
            }
                $result[$groupKey][$key] = $value;
        }
        return new static($result);
    }

    public function map(Closure $selector): static
    {
        $result = [];

        foreach ($this->array as $key => $value) {
            $result[$key] = $selector($value, $key);
        }
        return new static($result);
    }

    public function mapKey(Closure $selector): static
    {
        $result = [];
        foreach ($this->array as $key => $value) {
            $result[$selector($value, $key)] = $value;
        }
        return new static($result);
    }


    /**
     * @return Collection<TKey, mixed>
     */
    #[Pure]
    public function flatten(): Collection
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

    public function first(?Closure $predicate = null, bool $throwIfNone = false): mixed
    {
        $filter = $predicate ?? static fn () => true;
        foreach ($this->array as $key => $value) {
            if ($filter($value, $key)) {
                return $value;
            }
        }
        if($throwIfNone){
            throw new OutOfBoundsException("No first element found");
        }
        return null;
    }

    #[Pure]
    public function hasKey(mixed $key): bool
    {
        return array_key_exists($key, $this->array);
    }

    #[Pure]
    public function isEmpty(): bool
    {
        return count($this->array) === 0;
    }

    /**
     * @return Collection<TKey, TValue>
     */
    public function filter(Closure $shouldKeep): Collection
    {
        $result = [];
        foreach ($this->array as $key => $value) {
            if ($shouldKeep($value, $key)) {
                $result[$key] = $value;
            }
        }
        return new static($result);
    }


    /**
     * @return Collection<TKey, TValue>
     */
    #[Pure]
    public function skip(int $offset, bool $preserveKeys = true): Collection
    {
        return $this->slice($offset, null, $preserveKeys);
    }

    /**
     * @return Collection<TKey, TValue>
     */
    #[Pure]
    public function take(int $length, bool $preserveKeys = true, bool $throwIfLess = false): Collection
    {
        return $this->slice(0, $length, $preserveKeys, $throwIfLess);
    }

    /**
     * @param int $offset
     * @param int|null $length
     * @param bool $preserveKeys
     * @param bool $throwIfLess
     * @return Collection<TKey, TValue>
     */
    #[Pure]
    public function slice(int $offset = 0, ?int $length = null, bool $preserveKeys = false, bool $throwIfLess = false): Collection
    {
        $result = array_slice($this->array, $offset, $length, preserve_keys: $preserveKeys);
        if ($throwIfLess && count($result) < $length) {
            throw new OutOfBoundsException("Not enough items to slice");
        }
        return new static($result);
    }

    /**
     * @param Closure $predicate
     * @param bool $throwAllSkipped
     * @return Collection<TKey, TValue>
     */
    public function skipWhile(Closure $predicate, bool $throwAllSkipped = false): Collection
    {
        $fn = static fn ($value, $key) => !$predicate($value, $key);
        return $this->skipUntil($fn, $throwAllSkipped);
    }

    /**
     * @param Closure $predicate
     * @param bool $throwAllSkipped
     * @return Collection<TKey, TValue>
     */
    public function skipUntil(Closure $predicate, bool $throwAllSkipped = false): Collection
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

    /**
     * @return Collection<int, TKey>
     */
    #[Pure]
    public function keys(): Collection
    {
        return new static(array_keys($this->array));
    }

    /**
     * @return Collection<int, TValue>
     */
    #[Pure]
    public function values(): Collection
    {

        return new static(array_values($this->array));
    }

    public function every(Closure $predicate): bool
    {
        foreach ($this->array as $key => $value) {
            if (!$predicate($value, $key)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return Collection<TKey, TValue>
     */
    #[Pure]
    public function flip(): Collection
    {
        return new static(array_flip($this->array));
    }

    /**
     * @return Collection<TKey, TValue>
     */
    #[Pure]
    public function reverse(): Collection
    {
        return new static(array_reverse($this->array));
    }

    /**
     * @return Collection<TKey, TValue>
     */
    #[Pure]
    public function clone(): Collection
    {
        return clone $this;
    }

    #[Pure]
    public function implode(string $glue): Str
    {
        return new Str(implode($glue, $this->array));
    }

    /**
     * Alias for implode() for JS lovers
     * @param string $glue
     * @return string
     */
    #[Pure]
    public function join(string $glue): Str
    {
        return $this->implode($glue);
    }

    /**
     * @param string $columnName
     * @return Collection<TKey, mixed>
     */
    #[Pure]
    public function mapToColumn(string $columnName): Collection
    {
        return $this->map(static fn ($value) => $value[$columnName]);
    }

    /**
     * @param string $columnName
     * @return Collection<array-key, TValue>
     */
    #[Pure]
    public function keyByColumn(string $columnName): Enumerable
    {
        return $this->mapKey(fn ($value) => $value[$columnName]);
    }


    #[Pure]
    public function groupByColumn(string $columnName): Enumerable
    {
        return $this->groupBy(fn ($value): mixed => $value[$columnName]);
    }

    #[Pure]
    public function jsonSerialize(): mixed
    {
        // PHP will automatically call jsonSerialize on nested components.
        return $this->array;
    }

    #[Pure]
    public function excludeNull(): Enumerable
    {
        return $this->filter(fn ($value) => $value !== null);
    }
}
