# Stacked list

An extension on the Stack providing useful list methods.
But in practise you want to avoid this class! 
Most added methods have O(n) where n = length of StackedList or requested index.  

!!! Avoid using this class as methods are relativly slow.

Added methods:
* `add($value)`: add item in at the bottom of the stack
* `removeLast()`: remove item from bottom of the stack
* `$stack[offset]`: get item at certain index
* `$stack[offset]`: get item at certain index
* `$stack[offset] = $newValue`: overwrite item at certain index
* `unset($stack[offset])`: cut out item at index.
