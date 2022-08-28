<?php

namespace DataStructures\Set;

use PHPUnit\Framework\TestCase;

class FunctionalTest extends TestCase
{
    public function test_add(): void {
        $set = new Set();
        $set->add(1);
        self::assertSame(1, $set->count());
    }

    public function test_add_ignores_duplicates(): void {
        $set = new Set();
        $set->add(1);
        $set->add(1);
        self::assertSame(1, $set->count());
    }

    public function test_remove(): void {
        $set = new Set();
        $set->add(1);
        $set->remove(1);
        self::assertSame(0, $set->count());
    }

    public function test_remove_ignores_if_not_in_set(): void {
        $set = new Set();
        $set->add(1);
        $set->remove(2);
        self::assertSame(1, $set->count());
    }

    public function test_has(): void {
        $set = new Set();
        self::assertFalse($set->has(1));
        $set->add(1);
        self::assertTrue($set->has(1));
        self::assertFalse($set->has(2));
        $set->remove(1);
        self::assertFalse($set->has(1));
    }

    public function test_works_with_classes(): void {
        $set = new Set();
        $set->add($this);
        self::assertSame(1, $set->count());
        self::assertTrue($set->has($this));
        $set->remove($this);
        self::assertSame(0, $set->count());
        self::assertFalse($set->has($this));
    }

    public function test_works_with_strings(): void {
        $set = new Set();
        $set->add("1");
        self::assertSame(1, $set->count());
        self::assertTrue($set->has("1"));
        self::assertFalse($set->has("12"));
        $set->remove("1");
        self::assertSame(0, $set->count());
        self::assertFalse($set->has("1"));
    }

    public function test_works_with_floats(): void {
        $set = new Set();
        $set->add(1.1);
        self::assertSame(1, $set->count());
        self::assertTrue($set->has(1.1));
        self::assertFalse($set->has(1.01));
        $set->remove(1.1);
        self::assertSame(0, $set->count());
        self::assertFalse($set->has(1.1));
    }
}
