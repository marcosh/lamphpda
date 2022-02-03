<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\LinkedList;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\Brand\LinkedListBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\LinkedList;
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Extra\ExtraApply;
use Marcosh\LamPHPda\Typeclass\Traversable;

/**
 * @implements Traversable<LinkedListBrand>
 *
 * @psalm-immutable
 */
final class LinkedListTraversable implements Traversable
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
     * @param callable(A, B): B $f
     * @param B $b
     * @param HK1<LinkedListBrand, A> $a
     * @return B
     *
     * @psalm-pure
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function foldr(callable $f, $b, HK1 $a)
    {
        return (new LinkedListFoldable())->foldr($f, $b, $a);
    }

    /**
     * @template F of Brand
     * @template A
     * @template B
     * @param Applicative<F> $applicative
     * @param callable(A): HK1<F, B> $f
     * @param HK1<LinkedListBrand, A> $a
     * @return HK1<F, LinkedList<B>>
     *
     * @psalm-pure
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function traverse(Applicative $applicative, callable $f, HK1 $a): HK1
    {
        $listA = LinkedList::fromBrand($a);

        return $listA->foldr(
            /**
             * @param A $element
             * @param HK1<F, LinkedList<B>> $acc
             * @return HK1<F, LinkedList<B>>
             */
            static fn ($element, HK1 $acc) => (new ExtraApply($applicative))->lift2(
                [LinkedList::class, 'cons'],
                $f($element),
                $acc
            ),
            $applicative->pure(LinkedList::empty())
        );
    }
}
