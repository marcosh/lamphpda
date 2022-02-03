<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\LinkedList;

use Marcosh\LamPHPda\Brand\LinkedListBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\LinkedList;
use Marcosh\LamPHPda\Typeclass\Apply;

/**
 * @implements Apply<LinkedListBrand>
 *
 * @psalm-immutable
 */
final class LinkedListApply implements Apply
{
    /**
     * @template A
     * @template B
     * @param callable(A): B $f
     * @param HK1<LinkedListBrand, A> $a
     * @return LinkedList<B>
     *
     * @psalm-pure
     */
    public function map(callable $f, HK1 $a): LinkedList
    {
        return (new LinkedListFunctor())->map($f, $a);
    }

    /**
     * @template A
     * @template B
     * @param HK1<LinkedListBrand, callable(A): B> $f
     * @param HK1<LinkedListBrand, A> $a
     * @return LinkedList<B>
     *
     * @psalm-pure
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function apply(HK1 $f, HK1 $a): LinkedList
    {
        $listF = LinkedList::fromBrand($f);
        $listA = LinkedList::fromBrand($a);

        return $listF->eval(
            /**
             * @param callable(A): B $ff
             * @param LinkedList<B> $l
             */
            static fn (callable $ff, LinkedList $l) => $l->append($listA->map($ff)),
            LinkedList::empty()
        );
    }
}
