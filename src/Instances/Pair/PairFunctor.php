<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Pair;

use Marcosh\LamPHPda\Brand\PairBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Pair;
use Marcosh\LamPHPda\Typeclass\Functor;

/**
 * @template C
 *
 * @implements Functor<PairBrand<C>>
 *
 * @psalm-immutable
 */
final class PairFunctor implements Functor
{
    /**
     * @template A
     * @template B
     * @param callable(A): B $f
     * @param HK1<PairBrand<C>, A> $a
     * @return Pair<C, B>
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    public function map(callable $f, HK1 $a): HK1
    {
        return Pair::fromBrand($a)->eval(
            /**
             * @param C $c
             * @param A $b
             * @return Pair<C, B>
             */
            static fn (mixed $c, mixed $b): Pair => Pair::pair($c, $f($b))
        );
    }
}
