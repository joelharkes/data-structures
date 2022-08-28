# Set

A set or HashSet provides a data structure to store unique data in a without a defined order.

This data structure is very usefull if you need to check on uniques of data in a collection.
current implementation works for `string`, `float`, `int` and `classes`. 
Using these different types within a same set can lead to unexpected behaviour!

## Example

```php
$set = new Set();
$set->add(1);
$set->has(1); // true
$set->has(2); // false
$set->count(); // 1
$set->remove(1);
```

can also be used with classes:

```php
$set = new Set();
$set->add($user1);
$set->add($user2);
$set->has($user2); // true
```

Be vigilent that it checks on uniqueness in code objects not in database!
Retrieving the same database row twice would result in 2 separate objects being created by the ORM that you use ans thus could be added twice!
