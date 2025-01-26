<?php

declare(strict_types=1);

namespace DataStructures\StackedList;

use ArrayAccess;
use DataStructures\Stack\Node;
use DataStructures\Stack\Stack;
use InvalidArgumentException;
use OutOfBoundsException;

/**
 * @template TValue
 * @extends Stack<TValue>
 * @implements ArrayAccess<int, TValue>
 */
class StackedList extends Stack implements ArrayAccess
{
    public function offsetExists(mixed $offset): bool
    {
        // @phpstan-ignore function.alreadyNarrowedType (to force exception and unexpected type)
        if (!is_int($offset)) {
            throw new InvalidArgumentException("offset can only be integer");
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
        if ($offset === 0) {
            $this->firstNode = $this->firstNode->next;
        } else {
            $node = $this->findNode($offset - 1);
            // @phpstan-ignore property.nonObject (we know for sure next node exists as we checked the offset + 1)
            $node->next = $node->next->next;
        }
        // @phpstan-ignore assign.propertyType
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
        /** @var Node<TValue> $lastNode */
        $lastNode = $oneToLastNode->next;
        $oneToLastNode->next = null;
        // @phpstan-ignore assign.propertyType
        $this->length--;
        return $lastNode->value;
    }

    /**
     * @param mixed $offset
     * @return Node<TValue>
     */
    private function findNode(mixed $offset): Node
    {
        $this->assertValidOffset($offset);
        $current = 0;
        $node = $this->firstNode;
        while ($offset !== $current) {
            // @phpstan-ignore-next-line as above we assert next node for sure exists.
            $node = $node->next;
            $current++;
        }
        /** @var Node<TValue> $node */
        return $node;
    }

    private function assertValidOffset(mixed $offset): void
    {
        if (!is_int($offset)) {
            throw new InvalidArgumentException("offset can only be integer");
        }
        if ($offset < 0 || $offset >= $this->count()) {
            throw new OutOfBoundsException("cannot get index $offset of Stack with {$this->count()} nodes");
        }
    }
}
