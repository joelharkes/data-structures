<?php

declare(strict_types=1);
use \DataStructures\StackedList\StackedList;
test('add at end of stack', function () {
    $list = new StackedList();
    $list->push(2);
    $list->add(3);
    expect($list)->toBeIterableResult([2, 3]);
    expect($list->count())->toBe(2);
});
test('remove at end of list', function () {
    $list = new StackedList();
    $list->push(1);
    $list->push(2);
    $list->push(3);
    expect($list->removeLast())->toBe(1);
    expect($list->count())->toBe(2);
});
test('replace value with array access', function () {
    $list = new StackedList();
    $list->push(1);
    $list->push(2);
    $list->push(3);
    $list[2] = 5;
    $list[1] = 4;
    expect($list)->toBeIterableResult([3, 4, 5]);
});
test('get value with array access', function () {
    $list = new StackedList();
    $list->push(1);
    $list->push(2);
    $list->push(3);
    expect($list[0])->toBe(3);
    expect($list[1])->toBe(2);
});
test('unset removes value in stack', function () {
    $list = new StackedList();
    $list->push(1);
    $list->push(2);
    $list->push(3);
    unset($list[1]);
    expect($list)->toBeIterableResult([3, 1]);
    unset($list[0]);
    expect($list)->toBeIterableResult([1]);
});
