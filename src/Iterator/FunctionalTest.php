<?php

declare(strict_types=1);

namespace DataStructures\Iterator;

use ArrayIterator;
use PHPUnit\Framework\TestCase;
use Traversable;

class FunctionalTest extends TestCase
{
    public function test_count(): void
    {
        $iterator = new WrappedIterator(new ArrayIterator([1, 2, 3]));
        self::assertSame(3, $iterator->count());
    }

    public function test_any(): void
    {
        $iterator = new WrappedIterator(new ArrayIterator([1, 2, 3]));
        self::assertTrue($iterator->any(fn($value) => is_int($value)), 'return true if all match');
        self::assertTrue($iterator->any(fn($value) => $value === 1), 'return true if at least one matches');
        self::assertFalse($iterator->any(fn($value) => $value === 0), 'return false if none match');
    }

    public function test_all(): void
    {
        $iterator = new WrappedIterator(new ArrayIterator([1, 2, 3]));
        self::assertTrue($iterator->all(fn($value) => is_int($value)), 'return true if all match');
        self::assertFalse($iterator->all(fn($value) => $value === 1), 'return false if at least one does not match');
        self::assertFalse($iterator->all(fn($value) => $value === 0), 'return false if none match');
    }

    public function test_filter(): void
    {
        $iterator = new WrappedIterator(new ArrayIterator([1, 2, 3]));
        self::assertSame(1, $iterator->filter(fn($key) => $key === 1)->count());
    }

    public function test_filter_can_run_multiple_times(): void
    {
        $iterator = new WrappedIterator(new ArrayIterator([1, 2, 3]));
        $filtered = $iterator->filter(fn($key) => $key === 1);
        self::assertSame(1, $filtered->count());
        self::assertSame(1, $filtered->count());
    }

    public function test_map(): void
    {
        /** @var WrappedIterator<int, int> $iterator */
        $iterator = new WrappedIterator(new ArrayIterator([1, 2, 3]));
        self::assertValues([2, 4, 6], $iterator->map(fn($key) => $key * 2));
    }

    public function test_map_can_run_multiple_times(): void
    {
        $iterator = new WrappedIterator(new ArrayIterator([1, 2, 3]));
        $result = $iterator->map(fn($key) => $key * 2);
        self::assertValues([2, 4, 6], $result);
        self::assertValues([2, 4, 6], $result);
    }

    /**
     * @template TValue
     * @param Traversable<int, TValue> $list
     * @param array<int, TValue> $expectedValues
     * @return void
     */
    private static function assertValues(array $expectedValues, Traversable $list): void
    {
        foreach ($list as $key => $value) {
            self::assertSame($value, $expectedValues[$key]);
        }
    }
}
