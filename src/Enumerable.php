<?php declare(strict_types=1);

/* (c) Copyright Frontify Ltd., all rights reserved. */

namespace DataStructures;

use ArrayAccess;
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
    public function toArray(): array;

    /**
     * @template TKeyOut
     *
     * @param callable(TValue):TKeyOut $kefSelector
     * @return Enumerable<TKeyOut, Enumerable<int, TValue>>
     */
    public function groupBy(callable $kefSelector): Enumerable;

    /**
     * @template TValueOut
     * @param callable(TValue, ?TKey): TValueOut $valueMapper
     * @return Enumerable<TKey, TValueOut>
     */
    public function map(callable $valueMapper): Enumerable;

    /**
     * @template TKeyOut
     * @param callable(TValue, ?TKey): TKeyOut $keySelector
     * @return Enumerable<TKeyOut, TValue>
     */
    public function keyBy(callable $keySelector): Enumerable;

    /**
     * merges all values into one array.
     * @return Enumerable<TKey, mixed>
     */
    public function flatten(): Enumerable;
    /**
     * Filters out all null values.
     * @return Enumerable<TKey, TValue>
     */
    public function excludeNull(): Enumerable;

    /**
     * @param callable(TValue, ?TKey): bool|null $predicate
     * @param bool $throwIfNone
     * @return TValue|null
     * @psalm-return ($throwIfNone is true ? TValue : TValue|null)
     */
    public function first(callable $predicate = null, bool $throwIfNone = false): mixed;

    /**
     * @param TKey $key
     * @return bool
     */
    public function hasKey(mixed $key): bool;

    /**
     * @return bool true if the enumerable has no items.
     */
    public function isEmpty(): bool;

    /**
     * @param callable(TValue, ?TKey): bool $shouldKeep
     * @return Enumerable<TKey, TValue>
     */
    public function filter(callable $shouldKeep): Enumerable;


    /**
     * @param int $count amount of items to skip
     * @return Enumerable<TKey, TValue>
     */
    public function skip(int $count): Enumerable;

    /**
     * @param int $count amount of items to take
     * @param bool $throwIfLess if true, throws an exception if there are less items than $count
     * @return Enumerable<TKey, TValue> with maximum $count items.
     */
    public function take(int $count, bool $throwIfLess = false):Enumerable;

    /**
     * @param callable $predicate
     * @return static
     */
    public function skipWhile(callable $predicate, bool $throwAllSkipped = false): Enumerable;

    /**
     * @param callable $predicate
     * @return static
     */
    public function skipUntil(callable $predicate, bool $throwAllSkipped = false): Enumerable;

    /**
     * @return Enumerable<int, TKey>
     */
    public function keys(): Enumerable;

    /**
     * @return Enumerable<int, TValue>
     */
    public function values(): Enumerable;

    /**
     * @return int amount of items in the enumerable.
     */
    public function count(): int;

    /**
     * @param callable(TValue, ?TKey): bool $predicate
     * @return bool if at least one item matches the predicate.
     */
    public function any(callable $predicate): bool;

    /**
     * @param callable(TValue, ?TKey): bool $predicate
     * @return bool if all items match the predicate.
     */
    public function every(callable $predicate): bool;

    /**
     * @return Enumerable<TValue, TKey> with the keys and values swapped.
     */
    public function flip(): Enumerable;

    /**
     * @return string
     * @psalm-return (TValue is string ? string : never)
     */
    public function implode(string $glue): string;

    /**
     * TValue must be of type @see \ArrayAccess
     * @template TValueOut
     * @param string $arrayKey
     * @return Enumerable<TKey, TValueOut>
     */
    public function mapToColumn(string $arrayKey): Enumerable;

    /**
     * @template TKeyOut
     * @param string $columnName
     * @return Enumerable<TKeyOut, TValue>
     */
    public function keyByColumn(string $columnName): Enumerable;

    /**
     * @template TKeyOut
     * @param string $keyName
     * @return Enumerable<TKey, Enumerable<int, TValue>>
     */
    public function groupByColumn(string $keyName): Enumerable;
}
