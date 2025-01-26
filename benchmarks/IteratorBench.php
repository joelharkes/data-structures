<?php declare(strict_types=1);

/* (c) Copyright Frontify Ltd., all rights reserved. */

namespace benchmarks;

use DataStructures\Iterator\WrappedIterator;
use DataStructures\Map\Map;

class IteratorBench
{
    /**
     * @Revs(1000)
     * @Iterations(2)
     */
    public function benchIterator()
    {
        $data = range(1, 1000);
        $iterator = new WrappedIterator(new \ArrayIterator($data));
        $result = $iterator->skip(5)->take(900)
            ->filter(fn($v) => $v % 2 === 0)
            ->map(fn($v) => $v * 2)
            ->toArray();
    }

    /**
     * @Revs(1000)
     * @Iterations(2)
     */
    public function benchMap()
    {
        $data = range(1, 1000);
        $map = new Map($data);
        $result = $map->skip(5)->take(900)
            ->filter(fn($v) => $v % 2 === 0)
            ->map(fn($v) => $v * 2)
            ->toArray();
    }

    /**
     * @Revs(1000)
     * @Iterations(2)
     */
    public function benchStandard()
    {
        $data = range(1, 1000);
        $slice = array_slice($data, 5, 900);
        $filtered = array_filter($slice, fn($v) => $v % 2 === 0);
        $mapped = array_map(fn($v) => $v * 2, $filtered);
        $result = array_values($mapped);
    }
}
