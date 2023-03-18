<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Either;

use Marcosh\LamPHPda\Brand\EitherBrand;
use Marcosh\LamPHPda\Either;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Typeclass\Functor;

/**
 * @template C
 *
 * @implements Functor<EitherBrand<C>>
 *
 * @psalm-immutable
 */
final class EitherFunctor implements Functor
{
    /**
     * @template A
     * @template B
     * @param callable(A): B $f
     * @param HK1<EitherBrand<C>, A> $a
     * @return Either<C, B>
     *
     * @psalm-pure
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function map(callable $f, HK1 $a): Either
    {
        return Either::fromBrand($a)->eval(
            /**
             * @param C $b
             * @return Either<C, B>
             */
            static fn (mixed $b): Either => Either::left($b),
            /**
             * @param A $b
             * @return Either<C, B>
             */
            static fn (mixed $b): Either => Either::right($f($b))
        );
    }
}
