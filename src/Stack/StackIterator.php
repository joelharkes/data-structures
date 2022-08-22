<?php
declare(strict_types=1);

namespace DataStructures\Stack;

use Iterator;

/**
 * @template TValue
 */
class StackIterator implements Iterator
{
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
    function current(): mixed
    {
        return $this->node->value;
    }

    function key(): int
    {
        return $this->index;
    }

    function next(): void
    {
        ++$this->index;
        $this->node = $this->node?->next;
    }

    function rewind(): void
    {
        $this->index = 0;
        $this->node = $this->firstNode;
    }

    function valid(): bool
    {
        return $this->node !== null;
    }
}
