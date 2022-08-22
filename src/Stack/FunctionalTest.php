<?php

declare(strict_types=1);

namespace DataStructures\Stack;

use PHPUnit\Framework\TestCase;

final class FunctionalTest extends TestCase
{
    public function test_can_add_to_stack(): void {
        /** @var Stack<int> $stack */
        $stack = new Stack();
        $stack->push(1);
        self::assertEquals(1, $stack->count());
    }

    public function test_counts_stacks_size(): void {
        /** @var Stack<int> $stack */
        $stack = new Stack();
        self::assertEquals(0, $stack->count());
        $stack->push(1);
        self::assertEquals(1, $stack->count());
    }

    public function test_can_pop_returns_last_value(): void {
        /** @var Stack<int> $stack */
        $stack = new Stack();
        $stack->push(1);
        $stack->push(2);
        self::assertEquals(2, $stack->pop());
    }


    public function test_pop_removes_last_item_from_stack(): void {
        $stack = new Stack();
        $stack->push(2);
        $stack->push(1);
        self::assertEquals(1, $stack->pop());
    }


    public function test_pop_empty_stack_throw_is_empty_exception(): void {
        self::expectException(IsEmptyException::class);
        $stack = new Stack();
        $stack->pop();
    }

    public function test_can_for_each_over_stack(): void {
        /** @var Stack<int> $stack */
        $stack = new Stack();
        $stack->push(1);
        foreach ($stack as $index => $value){
            self::assertEquals(0, $index);
            self::assertEquals(1, $value);
        }
    }

    public function test_iterates_in_reverse_order(): void {
        /** @var Stack<int> $stack */
        $stack = new Stack();
        $stack->push(1);
        $stack->push(2);
        $iterator = $stack->getIterator();
        self::assertEquals(2, $iterator->current());
        $iterator->next();
        self::assertEquals(1, $iterator->current());
    }
}
