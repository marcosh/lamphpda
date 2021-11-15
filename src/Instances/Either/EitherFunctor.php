<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Either;

use Marcosh\LamPHPda\Brand\EitherBrand;
use Marcosh\LamPHPda\Either;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Typeclass\Functor;

/**
 * @implements Functor<EitherBrand>
 *
 * @psalm-immutable
 */
final class EitherFunctor implements Functor
{
    /**
     * @template A
     * @template B
     * @template C
     * @param callable(A): B $f
     * @param HK1<EitherBrand<C>, A> $a
     * @return Either<C, B>
     *
     * @psalm-pure
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function map(callable $f, HK1 $a): HK1
    {
        return Either::fromBrand($a)->eval(
            /**
             * @param C $b
             * @return Either<C, B>
             */
            fn ($b) => Either::left($b),
            /**
             * @param A $b
             * @return Either<C, B>
             */
            fn ($b) => Either::right($f($b))
        );
    }
}
