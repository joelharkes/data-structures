<?php

declare(strict_types=1);

namespace DataStructures\StackedList;

use PHPUnit\Framework\TestCase;
use Traversable;

class FunctionalTest extends TestCase
{
    public function test_add_at_end_of_stack(): void
    {
        $list = new StackedList();
        $list->push(2);
        $list->add(3);
        $this->assertValues($list, [2, 3]);
        self::assertSame(2, $list->count());
    }

    public function test_remove_at_end_of_list(): void
    {
        $list = new StackedList();
        $list->push(1);
        $list->push(2);
        $list->push(3);
        self::assertSame(1, $list->removeLast());
        self::assertSame(2, $list->count());
    }

    public function test_replace_value_with_array_access(): void
    {
        $list = new StackedList();
        $list->push(1);
        $list->push(2);
        $list->push(3);
        $list[2] = 5;
        $list[1] = 4;
        $this->assertValues($list, [3, 4, 5]);
    }

    public function test_get_value_with_array_access(): void
    {
        $list = new StackedList();
        $list->push(1);
        $list->push(2);
        $list->push(3);
        self::assertSame(3, $list[0]);
        self::assertSame(2, $list[1]);
    }

    public function test_unset_removes_value_in_stack(): void
    {
        $list = new StackedList();
        $list->push(1);
        $list->push(2);
        $list->push(3);
        unset($list[1]);
        $this->assertValues($list, [3, 1]);
        unset($list[0]);
        $this->assertValues($list, [1]);
    }

    /**
     * @template TValue
     * @param Traversable<int, TValue> $list
     * @param array<int, TValue> $expectedValues
     * @return void
     */
    private static function assertValues(Traversable $list, array $expectedValues): void
    {
        $count = 0;
        foreach ($list as $key => $value) {
            $count++;
            self::assertSame($value, $expectedValues[$key]);
        }
        self::assertSame(count($expectedValues), $count);
    }
}
