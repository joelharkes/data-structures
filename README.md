# data-structures
Data structures for PHP written in PHP.


* [Stack](./src/Stack)
* [StackedList](./src/StackedList)
* [Set](./src/Set) (HashSet)
* [Map](./src/Map) (HashMap, Dictionary)


## Iterator chaining

All iterators have been extended to chaining and delayed execution.

```php
$set = new \DataStructures\Set\Set();
$set->getIterator()
    ->filter(fn($value) => $value === 1)
    ->map(fn($value) => $value === 1)
    ->count(); // or all(), any().
```
