<?php

declare(strict_types=1);

namespace DataStructures\Stack;

use DataStructures\Iterator\IteratorExtensions;
use Iterator;
use LogicException;

/**
 * @template TValue
 * @implements Iterator<int, TValue>
 * @use IteratorExtensions<int, TValue>
 */
class StackIterator implements Iterator
{
    /** @use IteratorExtensions<int, TValue> */
    use IteratorExtensions;

    private int $index = 0;
    /** @var null|Node<TValue> */
    private ?Node $node;

    /**
     * @param null|Node<TValue> $firstNode
     */
    public function __construct(private readonly ?Node $firstNode)
    {
        $this->node = $this->firstNode;
    }

    /**
     * @return TValue
     */
    public function current(): mixed
    {
        if ($this->node === null) {
            throw new LogicException("cannot get current when current node is not ::valid()");
        }
        return $this->node->value;
    }

    public function key(): int
    {
        return $this->index;
    }

    public function next(): void
    {
        ++$this->index;
        $this->node = $this->node?->next;
    }

    public function rewind(): void
    {
        $this->index = 0;
        $this->node = $this->firstNode;
    }

    public function valid(): bool
    {
        return $this->node !== null;
    }
}
