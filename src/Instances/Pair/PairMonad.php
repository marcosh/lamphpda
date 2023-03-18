<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Pair;

use Marcosh\LamPHPda\Brand\PairBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Pair;
use Marcosh\LamPHPda\Typeclass\Monad;
use Marcosh\LamPHPda\Typeclass\Monoid;

/**
 * @template C
 *
 * @implements Monad<PairBrand<C>>
 *
 * @psalm-immutable
 */
final class PairMonad implements Monad
{
    /**
     * @param Monoid<C> $monoid
     */
    public function __construct(private readonly Monoid $monoid)
    {
    }

    /**
     * @template A
     * @template B
     * @param callable(A): B $f
     * @param HK1<PairBrand<C>, A> $a
     * @return Pair<C, B>
     *
     * @psalm-pure
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    public function map(callable $f, HK1 $a): HK1
    {
        return (new PairFunctor())->map($f, $a);
    }

    /**
     * @template A
     * @template B
     * @param HK1<PairBrand<C>, callable(A): B> $f
     * @param HK1<PairBrand<C>, A> $a
     * @return Pair<C, B>
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    public function apply(HK1 $f, HK1 $a): Pair
    {
        return (new PairApply($this->monoid))->apply($f, $a);
    }

    /**
     * @template A
     * @param A $a
     * @return Pair<C, A>
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    public function pure(mixed $a): Pair
    {
        return (new PairApplicative($this->monoid))->pure($a);
    }

    /**
     * @template A
     * @template B
     * @param HK1<PairBrand<C>, A> $a
     * @param callable(A): HK1<PairBrand<C>, B> $f
     * @return Pair<C, B>
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    public function bind(HK1 $a, callable $f): Pair
    {
        $pairA = Pair::fromBrand($a);

        return $pairA->eval(
            /**
             * @param C $ca
             * @param A $aa
             * @return Pair<C, B>
             */
            fn (mixed $ca, mixed $aa): Pair => Pair::fromBrand($f($aa))->eval(
                /**
                 * @param C $c
                 * @param B $b
                 * @return Pair<C, B>
                 */
                fn (mixed $c, mixed $b): Pair => Pair::pair($this->monoid->append($ca, $c), $b)
            )
        );
    }
}
