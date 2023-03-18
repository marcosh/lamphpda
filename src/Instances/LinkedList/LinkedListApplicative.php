<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\LinkedList;

use Marcosh\LamPHPda\Brand\LinkedListBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\LinkedList;
use Marcosh\LamPHPda\Typeclass\Applicative;

/**
 * @implements Applicative<LinkedListBrand>
 *
 * @psalm-immutable
 */
final class LinkedListApplicative implements Applicative
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
    public function pure(mixed $a): LinkedList
    {
        return LinkedList::cons($a, LinkedList::empty());
    }
}
