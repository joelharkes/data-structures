<?php

declare(strict_types=1);

namespace DataStructures\Set;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @template TValue of int|float|string|object
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
    public function add(mixed $value): void {
        $key = $this->getKey($value);
        $this->hasTable[$key] = $value;
    }

    /**
     * @param TValue $value
     * @return bool
     */
    public function has(mixed $value): bool {
        $key = $this->getKey($value);
        return array_key_exists($key, $this->hasTable);
    }

    /**
     * @param TValue $value
     * @return void
     */
    public function remove(mixed $value): void {
        $key = $this->getKey($value);
        unset($this->hasTable[$key]);
    }

    public function clear(): void {
        $this->hasTable = [];
    }

    /**
     * @param TValue $value
     * @return int|string
     */
    private function getKey(mixed $value): int|string
    {
        if(is_int($value) || is_string($value)){
            return $value;
        }
        if(is_float($value)){
            return (string) $value;
        }
        return spl_object_id($value);
    }

    /**
     * @return Traversable<int, TValue>
     */
    public function getIterator(): Traversable
    {
        $array = array_values($this->hasTable);
        return new \ArrayIterator($array);
    }

    public function count(): int
    {
        return count($this->hasTable);
    }
}
