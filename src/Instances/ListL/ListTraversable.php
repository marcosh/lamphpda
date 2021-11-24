<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\ListL;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\Brand\ListBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Extra\ExtraApply;
use Marcosh\LamPHPda\Typeclass\Traversable;

/**
 * @implements Traversable<ListBrand>
 *
 * @psalm-immutable
 */
final class ListTraversable implements Traversable
{
    /**
     * @template A
     * @template B
     * @param pure-callable(A, B): B $f
     * @param B $b
     * @param HK1<ListBrand, A> $a
     * @return B
     *
     * @psalm-pure
     */
    public function foldr(callable $f, $b, HK1 $a)
    {
        return (new ListFoldable())->foldr($f, $b, $a);
    }

    /**
     * @template A
     * @template B
     * @param pure-callable(A): B $f
     * @param HK1<ListBrand, A> $a
     * @return HK1<ListBrand, B>
     *
     * @psalm-pure
     */
    public function map(callable $f, HK1 $a): HK1
    {
        return (new ListFunctor())->map($f, $a);
    }

    /**
     * @template F of Brand
     * @template A
     * @template B
     * @param Applicative<F> $applicative
     * @param callable(A): HK1<F, B> $f
     * @param HK1<ListBrand, A> $a
     * @return HK1<F, HK1<ListBrand, B>>
     */
    public function traverse(Applicative $applicative, callable $f, HK1 $a): HK1
    {
        /** @var HK1<F, HK1<ListBrand, B>> $initial */
        $initial = $applicative->pure(ListBrand::fromList([]));

        /** @var HK1<F, HK1<ListBrand, B>> */
        return $this->foldr(
            /**
             * @param A $c
             * @param HK1<F, HK1<ListBrand, B>> $d
             * @return HK1<F, HK1<ListBrand, B>>
             */
            fn($c, $d) => (new ExtraApply($applicative))->lift2(
                /**
                 * @param B $h
                 * @param HK1<ListBrand, B> $t
                 */
                fn($h, $t) => ListBrand::fromList(array_merge([$h], ListBrand::toList($t))),
                $f($c),
                $d
            ),
            $initial,
            $a
        );
    }
}
