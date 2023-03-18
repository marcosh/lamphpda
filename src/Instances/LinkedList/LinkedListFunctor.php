<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\LinkedList;

use Marcosh\LamPHPda\Brand\LinkedListBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\LinkedList;
use Marcosh\LamPHPda\Typeclass\Functor;

/**
 * @implements Functor<LinkedListBrand>
 *
 * @psalm-immutable
 */
final class LinkedListFunctor implements Functor
{
    /**
     * @template A
     * @template B
     * @param callable(A): B $f
     * @param HK1<LinkedListBrand, A> $a
     * @return LinkedList<B>
     *
     * @psalm-pure
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function map(callable $f, HK1 $a): LinkedList
    {
        $listA = LinkedList::fromBrand($a);

        return $listA->eval(
            /**
             * @param A $element
             * @param LinkedList<B> $l
             * @return LinkedList<B>
             */
            static fn (mixed $element, LinkedList $l): LinkedList => LinkedList::cons($f($element), $l),
            LinkedList::empty()
        );
    }
}
