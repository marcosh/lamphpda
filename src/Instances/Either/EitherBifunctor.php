<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Either;

use Marcosh\LamPHPda\Brand\EitherBrand2;
use Marcosh\LamPHPda\Either;
use Marcosh\LamPHPda\HK\HK2;
use Marcosh\LamPHPda\Typeclass\Bifunctor;

/**
 * @implements Bifunctor<EitherBrand2>
 *
 * @psalm-immutable
 */
final class EitherBifunctor implements Bifunctor
{
    /**
     * @template A
     * @template B
     * @template C
     * @template D
     * @param callable(A): C $f
     * @param callable(B): D $g
     * @param HK2<EitherBrand2, A, B> $a
     * @return Either<C, D>
     */
    public function biMap(callable $f, callable $g, HK2 $a): Either
    {
        return Either::fromBrand2($a)->eval(
            /**
             * @param A $a
             * @return Either<C, D>
             */
            fn ($a) => Either::left($f($a)),
            /**
             * @param B $b
             * @return Either<C, D>
             */
            fn ($b) => Either::right($g($b))
        );
    }
}
