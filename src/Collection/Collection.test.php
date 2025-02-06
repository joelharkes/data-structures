<?php

declare(strict_types=1);
use DataStructures\Collection\Collection;

describe('A collection', function () {
    it('can be created emptily', function () {
        $collection = new Collection();
        expect($collection)->not->toBeNull();
        expect($collection->isEmpty())->toBeTrue();
    });

    it('can be created with array and it can be retrieved', function () {
        $collection = new Collection($arr = ['test' => 1]);
        expect($collection->toArray())->toBe($arr);
    });

    it('can be created from generator method', function () {
        $generator = function () {
            yield 'test' => 1;
        };
        $collection = Collection::fromTraversable($generator());
        expect($collection->toArray())->toBe(['test' => 1]);
    });

    it('can act as array', function () {
        $collection = new Collection();
        $collection['test'] = 1;
        expect($collection['test'])->toBe(1);
        $collection['test'] = 2;
        expect($collection['test'])->toBe(2);
        expect(count($collection))->toBe(1);
        expect(isset($collection['test']))->toBeTrue();
        unset($collection['test']);
        expect(isset($collection['test']))->toBeFalse();

        expect($collection['234'] ?? null)->toBeNull();
    });

    it('can be iterated over', function () {
        $collection = new Collection($arr = ['test' => 1]);
        foreach ($collection as $key => $value) {
            expect($key)->toBe('test');
            expect($value)->toBe(1);
        }
    });

    it('can check if has keys', function () {
        $collection = new Collection(['test' => 1]);
        expect($collection->hasKey('test'))->toBeTrue();
        expect($collection->offsetExists('test'))->toBeTrue();

        expect($collection->hasKey('test2'))->toBeFalse();
    });

    it('can check if has values', function () {
        $collection = new Collection(['test' => 1]);
        expect($collection->hasValue(1))->toBeTrue();
        expect($collection->hasValue(2))->toBeFalse();
    });

    it('can check if has contents based on predicate', function () {
        $collection = new Collection(['test' => 1, 'test2' => 2]);
        expect($collection->has(fn ($value, $key) => $value === 1 && $key === 'test'))->toBeTrue();
        expect($collection->has(fn ($value, $key) => $value === 4))->toBeFalse();
        expect($collection->every(fn ($value, $key) => $value === 1))->toBeFalse();
        expect($collection->every(fn ($value, $key) => $value >= 1))->toBeTrue();
    });

    it('can be filtered', function () {
        $collection = new Collection(['test' => 1, 'test2' => 2]);
        $filtered = $collection->filter(fn ($value, $key) => $value === 1 && $key === 'test');
        expect($filtered->toArray())->toBe(['test' => 1]);
    });

    it('can skip items', function () {
        $collection = new Collection(['test' => 1, 'test2' => 2]);
        $skipped = $collection->skip(1);
        expect($skipped->toArray())->toBe(['test2' => 2]);
        expect($collection->skipWhile(fn ($value, $key) => $value === 1)->toArray())->toBe(['test2' => 2]);
        expect($collection->skipUntil(fn ($value, $key) => $value === 2)->toArray())->toBe(['test2' => 2]);
    });
    it('can take items', function () {
        $collection = new Collection(['test' => 1, 'test2' => 2]);
        $taken = $collection->take(1);
        expect($taken->toArray())->toBe(['test' => 1]);
    });
    it('can throw on taking more then length', function () {
        $collection = new Collection(['test' => 1, 'test2' => 2]);
        expect(fn () => $collection->take(3, throwIfLess: true))->toThrow(OutOfBoundsException::class);
    });

    it('can flatten', function () {
        $collection = new Collection(['test' => [1, 2], 'test2' => [3, 4], 5]);
        expect($collection->flatten()->toArray())->toBe([1, 2, 3, 4, 5]);
    });

    it('can group by', function () {
        $collection = new Collection([
            2 => ['id' => 1, 'name' => 'test'],
            4 => ['id' => 2, 'name' => 'test2'],
            8 => ['id' => 3, 'name' => 'test'],
        ]);
        expect($collection->groupBy(fn ($value) => $value['name'], preserveKeys: true)->toArray())->toBe([
            'test' => [2 => ['id' => 1, 'name' => 'test'], 8 => ['id' => 3, 'name' => 'test']],
            'test2' => [ 4 => ['id' => 2, 'name' => 'test2']]
        ]);
    });

    it('can return first match', function () {
        $collection = new Collection(['test' => 1, 'test2' => 2]);
        expect($collection->first())->toBe(1);
        expect($collection->first(fn ($value, $key) => $key == 'test2'))->toBe(2);
        expect($collection->first(fn ($value, $key) => false))->toBeNull();
        expect(fn () => $collection->first(fn ($value, $key) => false, throwIfNone: true))->toThrow(OutOfBoundsException::class);
    });

    it('can get keys', function () {
        $collection = new Collection(['test' => 1, 'test2' => 2]);
        expect($collection->keys())->toBeIterableResult(['test', 'test2']);
    });

    it('can get values', function () {
        $collection = new Collection(['test' => 1, 'test2' => 4]);
        expect($collection->values())->toBeIterableResult([1, 4]);
    });

    it('can use handy column methods', function () {
        $collection = new Collection([
            ['id' => 1, 'name' => 'test'],
            ['id' => 2, 'name' => 'test2'],
            ['id' => 3, 'name' => 'test'],
        ]);
        expect($collection->mapToColumn('id')->toArray())->toBe([1, 2, 3]);
        expect($collection->mapToColumn('name')->toArray())->toBe(['test', 'test2', 'test']);
        expect($collection->keyByColumn('id')->toArray())->toBe([1 => ['id' => 1, 'name' => 'test'], 2 => ['id' => 2, 'name' => 'test2'], 3 => ['id' => 3, 'name' => 'test']]);
        expect($collection->groupByColumn('name')->toArray())->toBe(['test' => [['id' => 1, 'name' => 'test'], ['id' => 3, 'name' => 'test']], 'test2' => [['id' => 2, 'name' => 'test2']]]);
    });

});
