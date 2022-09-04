<?php

declare(strict_types=1);

namespace DataStructures\Map;

use PHPUnit\Framework\TestCase;

class FunctionalTest extends TestCase
{
    public function test_has(): void
    {
        $t = new Map(['a' => 1]);
        self::assertTrue($t->has('a'));
        self::assertFalse($t->has('b'));
    }

    public function test_has_value(): void
    {
        $t = new Map(['a' => 1]);
        self::assertTrue($t->hasValue(1));
        self::assertFalse($t->hasValue(2));
    }

    public function test_set_value(): void
    {
        $t = new Map(['a' => 1]);
        $t['b'] = 2;
        self::assertEquals(2, $t->count());
        self::assertTrue($t->has('b'));
    }

    public function test_count(): void
    {
        $map = new Map(['a' => 1]);
        self::assertCount(1, $map);
        $map['x'] = 1;
        self::assertCount(2, $map);
        unset($map['x']);
        self::assertCount(1, $map);
    }

    public function test_to_map(): void
    {
        $map = new Map(['a' => 1]);
        $newMap = $map->getIterator()
            ->map(fn($x) => $x + 1)
            ->toMap();
        self::assertCount(1, $newMap);
        self::assertNotSame($map, $newMap);
    }
}
