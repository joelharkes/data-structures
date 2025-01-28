# Iterator

This folder contains the Iterator data structure. It is similar to the Collection data structure but the main differences:
* Delayed execution of the operations, the operations are executed when the `toMap()` or `toArray()` method is called.
* It's a bit more memory efficient as it does not have to store all the data in memory, depending on your source.
* It's a bit slower than the Collection data structure as it wraps everytime with a new iterator.


## Example
    
```php
$iterator = new \DataStructures\Iterator\WrappedIterator(new ArrayIterator(['a'=>1,'b'=>2,'c'=>3]));
$newMap = $iterator
    ->filter(fn($value, $key) => $key <= 'b')
    ->map(fn($value, $key) => $key.$value)
    ->toMap();
// new map= ['a' => 'a1', 'b' => 'b2'];
```
