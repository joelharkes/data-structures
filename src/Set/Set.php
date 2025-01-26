<?php

declare(strict_types=1);

namespace DataStructures\Set;

use ArrayIterator;
use Countable;
use DataStructures\Iterator\WrappedIterator;
use Generator;
use IteratorAggregate;
use LogicException;

/**
 * technically only supports: int|float|string|object as values
 * @template TValue
 * @implements IteratorAggregate<int, TValue>
 */
class Set implements Countable, IteratorAggregate
{
    /**
     * @var array<int, TValue>|array<string, TValue>
     */
    private array $hasTable = [];

    /**
     * @param TValue $value
     * @return void
     */
    public function add(mixed $value): void
    {
        $key = $this->getKey($value);
        $this->hasTable[$key] = $value;
    }

    /**
     * @param TValue $value
     * @return bool
     */
    public function has(mixed $value): bool
    {
        $key = $this->getKey($value);
        return array_key_exists($key, $this->hasTable);
    }

    /**
     * @param TValue $value
     * @return void
     */
    public function remove(mixed $value): void
    {
        $key = $this->getKey($value);
        unset($this->hasTable[$key]);
    }

    public function clear(): void
    {
        $this->hasTable = [];
    }

    /**
     * @param TValue $value
     * @return int|string
     */
    private function getKey(mixed $value): int|string
    {
        if (is_int($value) || is_string($value)) {
            return $value;
        }
        if (is_float($value)) {
            return (string) $value;
        }
        if (!is_object($value)) {
            $type = gettype($value);
            throw new LogicException("value of $type not supported");
        }
        return spl_object_id($value);
    }

    /**
     * @return WrappedIterator<int, TValue>
     */
    public function getIterator(): WrappedIterator
    {
        $array = array_values($this->hasTable);
        return new WrappedIterator(new ArrayIterator($array));
    }

    public function count(): int
    {
        return count($this->hasTable);
    }

    /**
     * @template TItem
     * @param iterable<mixed, TItem> $traversable
     * @return Set<TItem>
     */
    public static function fromTraversable(iterable $traversable): Set
    {
        /** @var Set<TItem> $set */
        $set = new Set();
        foreach ($traversable as $item) {
            $set->add($item);
        }
        return $set;
    }

    /**
     * @template TItem
     * @param callable(): Generator<mixed, TItem> $generator
     * @return Set<TItem>
     */
    public static function fromClosure(callable $generator): Set
    {
        $iterable = $generator();
        return self::fromTraversable($iterable);
    }
}
