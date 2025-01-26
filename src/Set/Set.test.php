<?php

use DataStructures\Set\Set;

describe('a Set', function () {
    it('can add', function () {
        $set = new DataStructures\Set\Set();
        $set->add(1);
        expect($set->count())->toBe(1);
    });

    it('add ignores duplicates', function () {
        $set = new Set();
        $set->add(1);
        $set->add(1);
        expect($set->count())->toBe(1);
    });

    it('remove', function () {
        $set = new Set();
        $set->add(1);
        $set->remove(1);
        expect($set->count())->toBe(0);
    });

    it('remove ignores if not in set', function () {
        $set = new Set();
        $set->add(1);
        $set->remove(2);
        expect($set->count())->toBe(1);
    });

    it('has', function () {
        $set = new Set();
        expect($set->has(1))->toBeFalse();
        $set->add(1);
        expect($set->has(1))->toBeTrue();
        expect($set->has(2))->toBeFalse();
        $set->remove(1);
        expect($set->has(1))->toBeFalse();
    });

    it('works with classes', function () {
        $set = new Set();
        $set->add($this);
        expect($set->count())->toBe(1);
        expect($set->has($this))->toBeTrue();
        $set->remove($this);
        expect($set->count())->toBe(0);
        expect($set->has($this))->toBeFalse();
    });

    it('set works with stdclass', function () {
        $set = new Set();
        $object = new stdClass();
        $set->add($object);
        expect($set->count())->toBe(1);
        expect($set->has($object))->toBeTrue();
        expect($set->has(new stdClass()))->toBeFalse();
    });


    it('works with strings', function () {
        $set = new Set();
        $set->add("1");
        expect($set->count())->toBe(1);
        expect($set->has("1"))->toBeTrue();
        expect($set->has("12"))->toBeFalse();
        $set->remove("1");
        expect($set->count())->toBe(0);
        expect($set->has("1"))->toBeFalse();
    });

    it('works with floats', function () {
        $set = new Set();
        $set->add(1.1);
        expect($set->count())->toBe(1);
        expect($set->has(1.1))->toBeTrue();
        expect($set->has(1.01))->toBeFalse();
        $set->remove(1.1);
        expect($set->count())->toBe(0);
        expect($set->has(1.1))->toBeFalse();
    });

    it('can clear()', function () {
        $set = new Set();
        $set->add(2);
        $set->add(1);
        $set->clear();
        expect($set->count())->toBe(0);
        expect($set->has(2))->toBeFalse();
    });

    it('can make from array', function () {
        $set = Set::fromTraversable([1, 2, 3]);
        expect($set->count())->toBe(3);
        expect($set->has(2))->toBeTrue();
        expect($set->has(4))->toBeFalse();
    });

    it('can make from generator', function () {
        $generator = function () {
            yield 1;
            yield 2;
            yield 3;
        };
        $set = Set::fromClosure($generator);
        expect($set->count())->toBe(3);
        expect($set->has(2))->toBeTrue();
        expect($set->has(4))->toBeFalse();
    });

    it('can return an iterator', function () {
        $set = new Set();
        $set->add(1);
        $set->add(2);
        $set->add(3);
        expect($set->getIterator())->toBeIterableResult([1, 2, 3]);
    });

    it('arrays are not supported', function () {
        $set = new Set();
        $set->add([1]);
    })->throws(LogicException::class, 'value of array not supported');

})->covers(DataStructures\Set\Set::class);
