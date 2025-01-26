<?php

declare(strict_types=1);

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
     * @template TKeyOut of array-key
     * @param callable(TValue, ?TKey): TKey $selector
     * @param bool $preserveKeys
     * @return Enumerable<TKeyOut, Enumerable<($preserveKeys is true ? TKey : int), TValue>>
     */
    public function groupBy(callable $selector, bool $preserveKeys = false): Enumerable;

    /**
     * @template TValueOut
     * @param callable(TValue, ?TKey): TValueOut $selector
     * @return Enumerable<TKey, TValueOut>
     */
    public function map(callable $selector): Enumerable;

    /**
     * @template TKeyOut
     * @param callable(TValue, ?TKey): TKeyOut $selector
     * @return Enumerable<TKeyOut, TValue>
     */
    public function mapKey(callable $selector): Enumerable;

    /**
     * merges all values into one array, loses keys.
     * @return Enumerable<int, mixed>
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
     * @param TValue $value
     * @return bool
     */
    public function hasValue(mixed $value): bool;

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
     * @param int $offset amount of items to skip
     * @return Enumerable<TKey, TValue>
     */
    public function skip(int $offset): Enumerable;

    /**
     * @param int $length amount of items to take
     * @param bool $preserveKeys
     * @param bool $throwIfLess throws an exception if there are less items than $length
     * @return Enumerable<($preserveKeys is true ? TKey : int), TValue> with maximum $length items.
     */
    public function take(int $length, bool $preserveKeys = true, bool $throwIfLess = false): Enumerable;

    /**
     * @param int $offset amount of items to skip
     * @param int|null $length amount of items to take
     * @param bool $preserveKeys if true, keys are preserved
     * @param bool $throwIfLess if true, throws an exception if there are less items than $length in the result.
     * @return Enumerable<($preserveKeys is true ? TKey : int), TValue> with maximum $length items.
     */
    public function slice(int $offset = 0, ?int $length = null, bool $preserveKeys = false, bool $throwIfLess = false): Enumerable;


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
    public function has(callable $predicate): bool;

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


    // sadly the column methods are not typesafe as PHP does not support generic method calls without input parameter having the same type.
    /**
     * TValue must be of type @see \ArrayAccess
     * @param string $arrayKey
     * @return Enumerable<TKey, mixed>
     */
    public function mapToColumn(string $arrayKey): Enumerable;

    /**
     * @param string $columnName
     * @return Enumerable<array-key, TKey>
     */
    public function keyByColumn(string $columnName): Enumerable;

    /**
     * @param string $columnName
     * @param bool $preserveKeys if true, keys are preserved in the groups.
     * @return Enumerable<array-key, Enumerable<($preserveKeys is true ? TKey : int), TValue>>
     */
    public function groupByColumn(string $columnName, bool $preserveKeys = false): Enumerable;
}
