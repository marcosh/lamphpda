<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Either;

use Marcosh\LamPHPda\Brand\EitherBrand;
use Marcosh\LamPHPda\Either;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Typeclass\Monad;

/**
 * @implements Monad<EitherBrand>
 *
 * @psalm-immutable
 */
final class EitherMonad implements Monad
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
        return (new EitherApply())->apply($f, $a);
    }

    /**
     * @template A
     * @template B
     * @param A $a
     * @return Either<B, A>
     *
     * @psalm-pure
     */
    public function pure($a): Either
    {
        return (new EitherApplicative())->pure($a);
    }

    /**
     * @template A
     * @template B
     * @template C
     * @param HK1<EitherBrand<C>, A> $a
     * @param callable(A): HK1<EitherBrand<C>, B> $f
     * @return Either<C, B>
     *
     * @psalm-pure
     */
    public function bind(HK1 $a, callable $f): Either
    {
        $eitherA = Either::fromBrand($a);

        return $eitherA->eval(
            /**
             * @param C $c
             * @return Either<C, B>
             */
            fn($c) => Either::left($c),
            /**
             * @param A $b
             * @return Either<C, B>
             */
            fn($b) => Either::fromBrand($f($b))
        );
    }
}
