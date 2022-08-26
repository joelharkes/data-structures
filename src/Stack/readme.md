# Stack

Stack is a simple data structure where:

* push() adds an item in the front.
* pop() takes of the last item added and returns it.

This stack is iterable, meaning you can call `foreach($stack as $element)` in PHP but, it will start iterating in
reverse order:
meaning the last item added to the stack will be provided first.

## Example

```php
$stack = new Stack();
$stack->push(1);
$stack->push(2);
foreach($stack as $value){
    echo $value; // prints: 21
}
$lastAddedValue = $stack->pop(); // = 2
```

