<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\LinkedList;

use Marcosh\LamPHPda\Brand\LinkedListBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\LinkedList;
use Marcosh\LamPHPda\Typeclass\Monad;

/**
 * @implements Monad<LinkedListBrand>
 *
 * @psalm-immutable
 */
final class LinkedListMonad implements Monad
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
        return (new LinkedListApply())->apply($f, $a);
    }

    /**
     * @template A
     * @param A $a
     * @return LinkedList<A>
     *
     * @psalm-pure
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function pure($a): LinkedList
    {
        return (new LinkedListApplicative())->pure($a);
    }

    /**
     * @template A
     * @template B
     * @param HK1<LinkedListBrand, A> $a
     * @param callable(A): HK1<LinkedListBrand, B> $f
     * @return LinkedList<B>
     *
     * @psalm-pure
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function bind(HK1 $a, callable $f): LinkedList
    {
        $listA = LinkedList::fromBrand($a);

        return $listA->eval(
            /**
             * @param A $element
             * @param LinkedList<B> $l
             * @return LinkedList<B>
             */
            static fn ($element, LinkedList $l) => $l->append(LinkedList::fromBrand($f($element))),
            LinkedList::empty()
        );
    }
}
