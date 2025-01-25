<?php

declare(strict_types=1);
use \DataStructures\Iterator\WrappedIterator;
test('count', function () {
    $iterator = new WrappedIterator(new ArrayIterator([1, 2, 3]));
    self::assertSame(3, $iterator->count());
});
test('any', function () {
    $iterator = new WrappedIterator(new ArrayIterator([1, 2, 3]));
    self::assertTrue($iterator->any(fn($value) => is_int($value)), 'return true if all match');
    self::assertTrue($iterator->any(fn($value) => $value === 1), 'return true if at least one matches');
    self::assertFalse($iterator->any(fn($value) => $value === 0), 'return false if none match');
});
test('all', function () {
    $iterator = new WrappedIterator(new ArrayIterator([1, 2, 3]));
    self::assertTrue($iterator->all(fn($value) => is_int($value)), 'return true if all match');
    self::assertFalse($iterator->all(fn($value) => $value === 1), 'return false if at least one does not match');
    self::assertFalse($iterator->all(fn($value) => $value === 0), 'return false if none match');
});
test('filter', function () {
    $iterator = new WrappedIterator(new ArrayIterator([1, 2, 3]));
    self::assertSame(1, $iterator->filter(fn($key) => $key === 1)->count());
});
test('filter can run multiple times', function () {
    $iterator = new WrappedIterator(new ArrayIterator([1, 2, 3]));
    $filtered = $iterator->filter(fn($key) => $key === 1);
    self::assertSame(1, $filtered->count());
    self::assertSame(1, $filtered->count());
});
test('map', function () {
    /** @var WrappedIterator<int, int> $iterator */
    $iterator = new WrappedIterator(new ArrayIterator([1, 2, 3]));
    expect($iterator->map(fn($key) => $key * 2))->toBeIterableResult([2, 4, 6]);
});
test('map can run multiple times', function () {
    $iterator = new WrappedIterator(new ArrayIterator([1, 2, 3]));
    $result = $iterator->map(fn($key) => $key * 2);
    expect($result)->toBeIterableResult([2, 4, 6]);
});
test('skip iterator', function () {
    $iterator = new WrappedIterator(new ArrayIterator([1, 2, 3]));
    $result = $iterator->skip(2);
    expect($result)->toBeIterableResult([ 2 => 3]);
});
test('take iterator', function () {
    $iterator = new WrappedIterator(new ArrayIterator([1, 2, 3]));
    $result = $iterator->take(2);
    expect($result)->toBeIterableResult([1,2]);
});
test('flatten iterator', function () {
    $iterator = new WrappedIterator(new ArrayIterator([[1, 2], [3], 4]));
    $result = $iterator->flatten();
    expect($result)->toBeIterableResult([1,2,3,4]);
});
