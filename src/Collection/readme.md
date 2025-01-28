# Collection

A Collection provides a handy wrapper around PHP natives array which is mostly already an iterable HashMap but with some weird List quirks.

This data structure is very useful if you need to check on uniques of data in a collection based on a certain identifier often a string or an int.

! Currently only int and string keys are properly supported, like PHP native arrays.

## Naming

I tried to be as consistent as possible with the naming of the methods, sticking close to the PHP native array functions
and general naming conventions among other programming languages (like javascript).

Naming Considerations:
* array_walk => each()
* array_filter => filter()
* array_map => map()
* array_reduce => reduce()
* array_keys => keys()
* in_array, contains() => `hasValue()` explicit naming to avoid confusion in php.
* array_key_exists => `hasKey()` 
* .any(), .some() => has() (so it matches the above)
* .every() => .all() (so it matches the above)

## Example

```php
$collection = new \DataStructures\Collection\Collection();
$collection["a"] = 'aValue';
$collection->hasKey("a"); // true
$collection->hasKey('b'); // false
$collection->count(); // 1
unset($collection['a'])w;
```

all iterable functions are available as well.

```php
$collection = new \DataStructures\Collection\Collection(['a'=>1,'b'=>2,'c'=>3]);
$mappedAsync = $collection->getIterator()
    ->filter(fn($value, $key) => $key <= 'b')
    ->map(fn($value, $key) => $key.$value)
    ->toCollection(); // new Collection(['a' => 'a1', 'b' => 'b2']);
$length = $mappedAsync->count();
```
