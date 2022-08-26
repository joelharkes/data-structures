<?php

namespace DataStructures\Stack;
use Traversable;
use IteratorAggregate;

/**
 * @template TValue
 * @implements IteratorAggregate<int, TValue>
 */
class Stack implements IteratorAggregate, \Countable
{
    private int $length = 0;

    /**
     * @var null|Node<TValue>
     */
    private ?Node $firstNode = null;

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
        $toRemove  = $this->firstNode ?? throw new IsEmptyException();
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
	function getIterator(): Traversable {
        return new StackIterator($this->firstNode);
	}
}
