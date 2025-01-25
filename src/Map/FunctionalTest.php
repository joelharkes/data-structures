<?php

declare(strict_types=1);
use \DataStructures\Map\Map;
test('has', function () {
    $t = new Map(['a' => 1]);
    expect($t->has('a'))->toBeTrue();
    expect($t->has('b'))->toBeFalse();
});
test('has value', function () {
    $t = new Map(['a' => 1]);
    expect($t->hasValue(1))->toBeTrue();
    expect($t->hasValue(2))->toBeFalse();
});
test('set value', function () {
    $t = new Map(['a' => 1]);
    $t['b'] = 2;
    self::assertEquals(2, $t->count());
    expect($t->has('b'))->toBeTrue();
});
test('count', function () {
    $map = new Map(['a' => 1]);
    self::assertCount(1, $map);
    $map['x'] = 1;
    self::assertCount(2, $map);
    unset($map['x']);
    self::assertCount(1, $map);
});
test('to map', function () {
    $map = new Map(['a' => 1]);
    $newMap = $map->getIterator()
        ->map(fn($x) => $x + 1)
        ->toMap();
    self::assertCount(1, $newMap);
    self::assertNotSame($map, $newMap);
});
