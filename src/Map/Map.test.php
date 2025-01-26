<?php

declare(strict_types=1);
use DataStructures\Map\Map;

describe('A map', function () {
    it('can be created emptily', function () {
        $map = new Map();
        expect($map)->not->toBeNull();
        expect($map->isEmpty())->toBeTrue();
    });

    it('can be created with array and it can be retrieved', function () {
        $map = new Map($arr = ['test' => 1]);
        expect($map->toArray())->toBe($arr);
    });

    it('can be created from generator method', function () {
        $generator = function () {
            yield 'test' => 1;
        };
        $map = Map::fromTraversable($generator());
        expect($map->toArray())->toBe(['test' => 1]);
    });

    it('can act as array', function () {
        $map = new Map();
        $map['test'] = 1;
        expect($map['test'])->toBe(1);
        $map['test'] = 2;
        expect($map['test'])->toBe(2);
        expect(count($map))->toBe(1);
        expect(isset($map['test']))->toBeTrue();
        unset($map['test']);
        expect(isset($map['test']))->toBeFalse();

        expect($map['234'] ?? null)->toBeNull();
    });

    it('can be iterated over', function () {
        $map = new Map($arr = ['test' => 1]);
        foreach ($map as $key => $value) {
            expect($key)->toBe('test');
            expect($value)->toBe(1);
        }
    });

    it('can check if has keys', function () {
        $map = new Map(['test' => 1]);
        expect($map->hasKey('test'))->toBeTrue();
        expect($map->offsetExists('test'))->toBeTrue();

        expect($map->hasKey('test2'))->toBeFalse();
    });

    it('can check if has values', function () {
        $map = new Map(['test' => 1]);
        expect($map->hasValue(1))->toBeTrue();
        expect($map->hasValue(2))->toBeFalse();
    });

    it('can check if has contents based on predicate', function () {
        $map = new Map(['test' => 1, 'test2' => 2]);
        expect($map->has(fn ($value, $key) => $value === 1 && $key === 'test'))->toBeTrue();
        expect($map->has(fn ($value, $key) => $value === 4))->toBeFalse();
        expect($map->every(fn ($value, $key) => $value === 1))->toBeFalse();
        expect($map->every(fn ($value, $key) => $value >= 1))->toBeTrue();
    });

    it('can be filtered', function () {
        $map = new Map(['test' => 1, 'test2' => 2]);
        $filtered = $map->filter(fn ($value, $key) => $value === 1 && $key === 'test');
        expect($filtered->toArray())->toBe(['test' => 1]);
    });

    it('can skip items', function () {
        $map = new Map(['test' => 1, 'test2' => 2]);
        $skipped = $map->skip(1);
        expect($skipped->toArray())->toBe(['test2' => 2]);
        expect($map->skipWhile(fn ($value, $key) => $value === 1)->toArray())->toBe(['test2' => 2]);
        expect($map->skipUntil(fn ($value, $key) => $value === 2)->toArray())->toBe(['test2' => 2]);
    });
    it('can take items', function () {
        $map = new Map(['test' => 1, 'test2' => 2]);
        $taken = $map->take(1);
        expect($taken->toArray())->toBe(['test' => 1]);
    });
    it('can throw on taking more then length', function () {
        $map = new Map(['test' => 1, 'test2' => 2]);
        expect(fn () => $map->take(3, throwIfLess: true))->toThrow(OutOfBoundsException::class);
    });

    it('can flatten', function () {
        $map = new Map(['test' => [1, 2], 'test2' => [3, 4]]);
        expect($map->flatten()->toArray())->toBe([1, 2, 3, 4]);
    });

    it('can group by', function () {
        $map = new Map([
            2 => ['id' => 1, 'name' => 'test'],
            4 => ['id' => 2, 'name' => 'test2'],
            8 => ['id' => 3, 'name' => 'test'],
        ]);
        expect($map->groupBy(fn ($value) => $value['name'], preserveKeys: true)->toArray())->toBe([
            'test' => [2 => ['id' => 1, 'name' => 'test'], 8 => ['id' => 3, 'name' => 'test']],
            'test2' => [ 4 => ['id' => 2, 'name' => 'test2']]
        ]);
    });

    it('can return first match', function () {
        $map = new Map(['test' => 1, 'test2' => 2]);
        expect($map->first())->toBe(1);
        expect($map->first(fn ($value, $key) => $key == 'test2'))->toBe(2);
    });

    it('can get keys', function () {
        $map = new Map(['test' => 1, 'test2' => 2]);
        expect($map->keys())->toBeIterableResult(['test', 'test2']);
    });

    it('can get values', function () {
        $map = new Map(['test' => 1, 'test2' => 4]);
        expect($map->values())->toBeIterableResult([1, 4]);
    });

    it('can use handy column methods', function () {
        $map = new Map([
            ['id' => 1, 'name' => 'test'],
            ['id' => 2, 'name' => 'test2'],
            ['id' => 3, 'name' => 'test'],
        ]);
        expect($map->mapToColumn('id')->toArray())->toBe([1, 2, 3]);
        expect($map->mapToColumn('name')->toArray())->toBe(['test', 'test2', 'test']);
        expect($map->keyByColumn('id')->toArray())->toBe([1 => ['id' => 1, 'name' => 'test'], 2 => ['id' => 2, 'name' => 'test2'], 3 => ['id' => 3, 'name' => 'test']]);
        expect($map->groupByColumn('name')->toArray())->toBe(['test' => [['id' => 1, 'name' => 'test'], ['id' => 3, 'name' => 'test']], 'test2' => [['id' => 2, 'name' => 'test2']]]);
    });

});
