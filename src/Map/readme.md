# Map

A Map provides a handy wrapper around PHP natives array which is mostly already a HashMap.

This data structure is very useful if you need to check on uniques of data in a collection based on a certain identifier often a string or an int.

!!! Currently only int and string keys are properly supported.

## Example

```php
$map = new \DataStructures\Map\Map();
$map->set("a", 'aValue');
$map->has("a"); // true
$map->has('b'); // false
$map->count(); // 1
$map->remove('a');
```

all iterable functions are available as well:

```php
$map = new \DataStructures\Map\Map(['a'=>1,'b'=>2,'c'=>3]);
$newMap = $map->getIterator()
    ->filter(fn($value, $key) => $key <= 'b')
    ->map(fn($value, $key) => $key.$value)
    ->toMap();
// new map= ['a' => 'a1', 'b' => 'b2'];
$length = $newMap->count();
```
