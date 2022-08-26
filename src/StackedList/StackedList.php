<?php
declare(strict_types=1);

namespace DataStructures\StackedList;

use ArrayAccess;
use DataStructures\Stack\Node;
use DataStructures\Stack\Stack;

/**
 * @template TValue
 * @extends Stack<TValue>
 * @implements ArrayAccess<int, TValue>
 */
class StackedList extends Stack implements ArrayAccess
{

    public function offsetExists(mixed $offset): bool
    {
        if (!is_int($offset)) {
            throw new \InvalidArgumentException("offset can only be integer");
        }
        return $offset <= $this->count();
    }

    /**
     * @return TValue
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->findNode($offset)->value;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->findNode($offset)->value = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->assertValidOffset($offset);
        $node = $this->findNode($offset - 1);
        $node->next = $node->next->next;
        $this->length--;
    }

    /**
     * Add item at the end of the list.
     *
     * Warning: 0(n) where n = length of the list.
     * @param TValue $value
     * @return void
     */
    public function add(mixed $value): void
    {
        $lastNode = $this->findNode($this->count() - 1);
        $lastNode->next = new Node($value);
        $this->length++;
    }

    /**
     * Remove item at the end of the list.
     *
     * Warning: 0(n) where n = length of the list.
     * @return TValue
     */
    public function removeLast(): mixed
    {
        $oneToLastNode = $this->findNode($this->count() - 2);
        $lastNode = $oneToLastNode->next;
        $oneToLastNode->next = null;
        $this->length--;
        return $lastNode->value;
    }

    private function findNode(int $offset): Node
    {
        $this->assertValidOffset($offset);
        $current = 0;
        $node = $this->firstNode;
        while ($offset !== $current) {
            $node = $node->next;
            $current++;
        }
        return $node;
    }

    private function assertValidOffset($offset)
    {
        if (!is_int($offset)) {
            throw new \InvalidArgumentException("offset can only be integer");
        }
        if ($offset < 0 || $offset >= $this->count()) {
            throw new \OutOfBoundsException("cannot get index $offset of Stack with {$this->count()} nodes");
        }
    }
}
