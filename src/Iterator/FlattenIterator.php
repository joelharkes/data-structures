<?php

declare(strict_types=1);



namespace DataStructures\Iterator;

use Iterator;

/**
 * @template TKey
 * @template-covariant TValue
 * @extends WrappedIterator<TKey, TValue>
 */
class FlattenIterator extends WrappedIterator
{
    private ?Iterator $subIterator = null;

    private int $index = 0;

    /**
     * @param Iterator<TKey, Iterator<mixed, TValue>|array<array-key, TValue>> $iterator
     */
    public function __construct(Iterator $iterator, private bool $preserveKeys = false)
    {
        // @phpstan-ignore argument.type (have to do some type waring, to give a typed result)
        parent::__construct($iterator);
    }

    public function current(): mixed
    {
        if ($this->subIterator) {
            return $this->subIterator->current();
        }
        $top = parent::current();
        if (!is_iterable($top)) {
            return $top;
        }
        $this->subIterator = self::ensureIterator($top);
        return $this->subIterator->current();
    }

    public function next(): void
    {
        $this->index++;
        if (!$this->subIterator) {
            parent::next();
            return;
        }

        $this->subIterator->next();
        if (!$this->subIterator->valid()) {
            parent::next();
            $this->subIterator = null;
        }
    }

    public function valid(): bool
    {
        return parent::valid();
    }

    public function rewind(): void
    {
        parent::rewind();
        $this->index = 0;
        $this->subIterator = null;
    }

    public function key(): mixed
    {
        if ($this->preserveKeys) {
            return parent::key();
        }
        return $this->index;
    }

    private static function ensureIterator(mixed $value): Iterator
    {
        if ($value instanceof Iterator) {
            return $value;
        }
        // consider object support? ArrayIterator supports it.
        if (is_array($value)) {
            return new \ArrayIterator($value);
        }

        throw new \InvalidArgumentException('Value must be an array or an \Iterator.');
    }
}
