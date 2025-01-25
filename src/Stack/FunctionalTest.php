<?php declare(strict_types=1);
use \DataStructures\Stack\IsEmptyException;
use \DataStructures\Stack\Stack;
test('can add to stack', function () {
    /** @var Stack<int> $stack */
    $stack = new Stack();
    $stack->push(1);
    expect($stack->count())->toBe(1);;
});
test('counts stacks size', function () {
    /** @var Stack<int> $stack */
    $stack = new Stack();
    expect($stack->count())->toBe(0);;
    $stack->push(1);
    expect($stack->count())->toBe(1);;
});
test('can pop returns last value', function () {
    /** @var Stack<int> $stack */
    $stack = new Stack();
    $stack->push(1);
    $stack->push(2);
    expect($stack->pop())->toBe(2);;
});
test('pop removes last item from stack', function () {
    $stack = new Stack();
    $stack->push(2);
    $stack->push(1);
    expect($stack->pop())->toBe(1);;
});
test('pop empty stack throw is empty exception', function () {
    self::expectException(IsEmptyException::class);
    $stack = new Stack();
    $stack->pop();
});
test('can for each over stack', function () {
    /** @var Stack<int> $stack */
    $stack = new Stack();
    $stack->push(1);
    foreach ($stack as $index => $value) {
        expect($index)->toBe(0);;
        expect($value)->toBe(1);;
    }
});
test('iterates in reverse order', function () {
    /** @var Stack<int> $stack */
    $stack = new Stack();
    $stack->push(1);
    $stack->push(2);
    $iterator = $stack->getIterator();
    expect($iterator->current())->toBe(2);;
    $iterator->next();
    expect($iterator->current())->toBe(1);;
});
