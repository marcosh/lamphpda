<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Either;

use Marcosh\LamPHPda\Brand\EitherBrand;
use Marcosh\LamPHPda\Either;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Typeclass\Apply;

/**
 * @implements Apply<EitherBrand>
 *
 * @psalm-immutable
 */
final class EitherApply implements Apply
{
    /**
     * @template A
     * @template B
     * @template C
     * @param callable(A): B $f
     * @param HK1<EitherBrand<C>, A> $a
     * @return Either<C, B>
     */
    public function map(callable $f, HK1 $a): HK1
    {
        return (new EitherFunctor())->map($f, $a);
    }

    /**
     * @template A
     * @template B
     * @template C
     * @param HK1<EitherBrand<C>, callable(A): B> $f
     * @param HK1<EitherBrand<C>, A> $a
     * @return Either<C, B>
     *
     * @psalm-pure
     */
    public function apply(HK1 $f, HK1 $a): Either
    {
        $eitherF = Either::fromBrand($f);
        $eitherA = Either::fromBrand($a);

        return $eitherF->eval(
            /**
             * @param C $c
             * @return Either<C, B>
             */
            fn($c) => Either::left($c),
            /**
             * @param callable(A): B $f
             * @return Either<C, B>
             */
            fn($f) => $eitherA->eval(
                /**
                 * @param C $c
                 * @return Either<C, B>
                 */
                fn($c) => Either::left($c),
                /**
                 * @param A $a
                 * @return Either<C, B>
                 */
                fn($a) => Either::right($f($a))
            )
        );
    }
}