<?php

declare(strict_types=1);



namespace DataStructures;

use ArrayAccess;
use Closure;
use JetBrains\PhpStorm\Pure;
use Stringable;
use Traversable;

/**
 * @template TKey
 * @template TValue
 * @extends Traversable<TKey, TValue>
 */
interface Enumerable extends Traversable
{
    /**
     * @return array<TKey, TValue>
     */
    #[Pure]
    public function toArray(): array;

    /**
     * @template TKeyOut of array-key
     * @param Closure(TValue, ?TKey): TKeyOut $selector
     * @return Enumerable<TKeyOut, Enumerable<TKey, TValue>> preserves keys in groups.
     */
    public function groupBy(Closure $selector): Enumerable;

    /**
     * @template TValueOut
     * @param Closure(TValue, ?TKey): TValueOut $selector
     * @return Enumerable<TKey, TValueOut>
     */
    public function map(Closure $selector): Enumerable;

    /**
     * @template TKeyOut
     * @param Closure(TValue, ?TKey): TKeyOut $selector
     * @return Enumerable<TKeyOut, TValue>
     */
    public function mapKey(Closure $selector): Enumerable;

    /**
     * merges all values into one array, loses keys.
     * @return Enumerable<int, mixed>
     */
    #[Pure]
    public function flatten(): Enumerable;
    /**
     * Filters out all null values.
     * @return Enumerable<TKey, TValue>
     */
    #[Pure]
    public function excludeNull(): Enumerable;

    /**
     * @param null|Closure(TValue, ?TKey): bool $predicate
     * @param bool $throwIfNone
     * @return TValue|null
     * @psalm-return ($throwIfNone is true ? TValue : TValue|null)
     */
    public function first(?Closure $predicate = null, bool $throwIfNone = false): mixed;

    /**
     * @param TKey $key
     * @return bool
     */
    #[Pure]
    public function hasKey(mixed $key): bool;

    /**
     * @param TValue $value
     * @return bool
     */
    #[Pure]
    public function hasValue(mixed $value): bool;

    /**
     * @return bool true if the enumerable has no items.
     */
    #[Pure]
    public function isEmpty(): bool;

    /**
     * @param Closure(TValue, ?TKey): bool $shouldKeep
     * @return Enumerable<TKey, TValue>
     */
    public function filter(Closure $shouldKeep): Enumerable;


    /**
     * @param int $offset amount of items to skip
     * @return Enumerable<TKey, TValue>
     */
    #[Pure]
    public function skip(int $offset): Enumerable;

    /**
     * @param int $length amount of items to take
     * @param bool $preserveKeys
     * @param bool $throwIfLess throws an exception if there are less items than $length
     * @return Enumerable<($preserveKeys is true ? TKey : int), TValue> with maximum $length items.
     */
    #[Pure]
    public function take(int $length, bool $preserveKeys = true, bool $throwIfLess = false): Enumerable;

    /**
     * @param int $offset amount of items to skip
     * @param int|null $length amount of items to take
     * @param bool $preserveKeys if true, keys are preserved
     * @param bool $throwIfLess if true, throws an exception if there are less items than $length in the result.
     * @return Enumerable<($preserveKeys is true ? TKey : int), TValue> with maximum $length items.
     */
    #[Pure]
    public function slice(int $offset = 0, ?int $length = null, bool $preserveKeys = false, bool $throwIfLess = false): Enumerable;


    /**
     * @param Closure $predicate
     * @return static
     */
    public function skipWhile(Closure $predicate, bool $throwAllSkipped = false): Enumerable;

    /**
     * @param Closure $predicate
     * @return static
     */
    public function skipUntil(Closure $predicate, bool $throwAllSkipped = false): Enumerable;

    /**
     * @return Enumerable<int, TKey>
     */
    #[Pure]
    public function keys(): Enumerable;

    /**
     * @return Enumerable<int, TValue>
     */
    #[Pure]
    public function values(): Enumerable;

    /**
     * @return int amount of items in the enumerable.
     */
    #[Pure]
    public function count(): int;

    /**
     * @param Closure(TValue, ?TKey): bool $predicate
     * @return bool if at least one item matches the predicate.
     */
    public function has(Closure $predicate): bool;

    /**
     * @param Closure(TValue, ?TKey): bool $predicate
     * @return bool if all items match the predicate.
     */
    public function every(Closure $predicate): bool;

    /**
     * @return Enumerable<TValue, TKey> with the keys and values swapped.
     */
    #[Pure]
    public function flip(): Enumerable;

    /**
     * Reverses the order of items in the array.
     * @return Enumerable<TKey, TValue>.
     */
    #[Pure]
    public function reverse(): Enumerable;

    /**
     * @return Enumerable<TKey, TValue>
     */
    #[Pure]
    public function clone(): Enumerable;

    /**
     * @return Stringable
     * @psalm-return (TValue is string ? string : never)
     */
    #[Pure]
    public function implode(string $glue): Stringable;


    // sadly the column methods are not typesafe as PHP does not support generic method calls without input parameter having the same type.
    /**
     * TValue must be of type @param string $columnName
     * @return Enumerable<TKey, mixed>
     *@see \ArrayAccess
     */
    #[Pure]
    public function mapToColumn(string $columnName): Enumerable;

    /**
     * @param string $columnName
     * @return Enumerable<array-key, TValue>
     */
    #[Pure]

    public function keyByColumn(string $columnName): Enumerable;

    /**
     * @param string $columnName
     * @return Enumerable<array-key, Enumerable<TKey, TValue>>
     */
    #[Pure]
    public function groupByColumn(string $columnName): Enumerable;
}
