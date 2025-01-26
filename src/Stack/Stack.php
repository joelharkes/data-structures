<?php

namespace DataStructures\Stack;

use Countable;
use Traversable;
use IteratorAggregate;

/**
 * @template TValue
 * @implements IteratorAggregate<int, TValue>
 */
class Stack implements IteratorAggregate, Countable
{
    /**
     * @var int<0, max>
     */
    protected int $length = 0;

    /**
     * @var null|Node<TValue>
     */
    protected ?Node $firstNode = null;

    /**
     * @template TItem
     * @param iterable<mixed, TItem> $param
     * @return Stack<TItem>
     */
    public static function fromTraversable(iterable $param): Stack
    {
        /** @var Stack<TItem> $stack */
        $stack = new Stack();
        foreach ($param as $value) {
            $stack->push($value);
        }
        return $stack;
    }

    /**
     * @param TValue $value
     */
    public function push(mixed $value): void
    {
        $newNode = new Node($value);
        $newNode->next = $this->firstNode;
        $this->firstNode = $newNode;
        ++$this->length;
    }

    /**
     * @return TValue
     */
    public function pop(): mixed
    {
        $toRemove = $this->firstNode ?? throw new IsEmptyException();
        $this->firstNode = $toRemove->next;
        return $toRemove->value;
    }

    public function count(): int
    {
        return $this->length;
    }

    /**
     * @return StackIterator<TValue>
     */
    public function getIterator(): StackIterator
    {
        return new StackIterator($this->firstNode);
    }
}
