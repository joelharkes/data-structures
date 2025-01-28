# data-structures
Data structures for PHP written in PHP.

Goal is to provide useful chainable methods for standard data structures, 
with a balance between as much type safety as possible and ease of use.

* [Stack](./src/Stack)
* [StackedList](./src/StackedList)
* [Set](./src/Set) (HashSet)
* [Collection](./src/Collection) (HashMap, Dictionary)


## Iterator chaining

All iterators have been extended to chaining and delayed execution.

```php
$set = new \DataStructures\Set\Set();
$set->getIterator()
    ->filter(fn($value) => $value === 1)
    ->map(fn($value) => $value === 1)
    ->count(); // or all(), any().
```
