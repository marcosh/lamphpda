<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\LinkedList;

use Marcosh\LamPHPda\Brand\LinkedListBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\LinkedList;
use Marcosh\LamPHPda\Typeclass\Foldable;

/**
 * @implements Foldable<LinkedListBrand>
 *
 * @psalm-immutable
 */
final class LinkedListFoldable implements Foldable
{
    /**
     * @template A
     * @template B
     * @param callable(A, B): B $f
     * @param B $b
     * @param HK1<LinkedListBrand, A> $a
     * @return B
     */
    public function foldr(callable $f, mixed $b, HK1 $a): mixed
    {
        $listA = LinkedList::fromBrand($a);

        return $listA->eval($f, $b);
    }
}
