<?php

declare(strict_types=1);

namespace DataStructures\Iterator;

use Closure;
use DataStructures\Set\Set;
use DataStructures\Stack\Stack;

/**
 * @template TKey
 * @template-covariant TValue
 */
trait IteratorExtensions
{
    /**
     * @param Closure(TValue, TKey=): bool $filterFn
     * @return FilterIterator<TKey, TValue>
     */
    public function filter(Closure $filterFn): WrappedIterator{
        return new FilterIterator($this, $filterFn);
    }

    /**
     * @template TMappedValue
     * @param Closure(TValue, TKey=): TMappedValue $mapFn
     * @return MapIterator<TKey, TMappedValue, TValue>
     * @phpstan-ignore-next-line we know its covariant because we only call the function on this iterator it's content
     */
    public function map(Closure $mapFn): WrappedIterator {
        /** @var MapIterator<TKey, TMappedValue, TValue> $iterator */
        $iterator = new MapIterator($this, $mapFn);
        return $iterator;
    }

    /**
     * @return Set<TValue>
     * @phpstan-ignore-next-line we know its covariant because we only call the function on this iterator it's content
     */
    public function toSet(): Set {
        return Set::fromTraversable($this);
    }


    /**
     * @return Stack<TValue>
     * @phpstan-ignore-next-line we know its covariant because we only call the function on this iterator it's content
     */
    public function toStack(): Stack {
        return Stack::fromTraversable($this);
    }

    public function count(): int {
        $length = 0;
        foreach($this as $_){
            $length ++;
        }
        return $length;
    }

    /**
     * @param callable(TValue, TKey=): bool $filterFn
     * @return bool
     */
    public function all(callable $filterFn): bool {
        foreach($this as $key => $value){
            if(!$filterFn($value, $key)){
                return false;
            }
        }
        return true;
    }

    /**
     * @param callable(TValue, TKey=): bool $filterFn
     * @return bool
     */
    public function any(callable $filterFn): bool {
        foreach($this as $key => $value){
            if($filterFn($value, $key)){
                return true;
            }
        }
        return false;
    }
}
