<?php
declare(strict_types=1);

namespace DataStructures\Stack;

/**
 * @template TValue
 */
class Node
{
    /** @var null|Node<TValue> */
    public ?Node $next = null;

    /**
     * @param TValue $value
     */
    public function __construct(public mixed $value)
    {
    }
}
