<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Either;

use Marcosh\LamPHPda\Brand\EitherBrand;
use Marcosh\LamPHPda\Either;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Typeclass\Monad;

/**
 * @template C
 *
 * @implements Monad<EitherBrand<C>>
 *
 * @psalm-immutable
 */
final class EitherMonad implements Monad
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
    public function map(callable $f, HK1 $a): HK1
    {
        return (new EitherFunctor())->map($f, $a);
    }

    /**
     * @template A
     * @template B
     * @param HK1<EitherBrand<C>, callable(A): B> $f
     * @param HK1<EitherBrand<C>, A> $a
     * @return Either<C, B>
     *
     * @psalm-pure
     *
     * @psalm-suppress LessSpecificImplementedReturnType
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
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function pure(mixed $a): Either
    {
        return (new EitherApplicative())->pure($a);
    }

    /**
     * @template A
     * @template B
     * @param HK1<EitherBrand<C>, A> $a
     * @param callable(A): HK1<EitherBrand<C>, B> $f
     * @return Either<C, B>
     *
     * @psalm-pure
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function bind(HK1 $a, callable $f): Either
    {
        $eitherA = Either::fromBrand($a);

        return $eitherA->eval(
            /**
             * @param C $c
             * @return Either<C, B>
             */
            static fn (mixed $c): Either => Either::left($c),
            /**
             * @param A $b
             * @return Either<C, B>
             */
            static fn (mixed $b): Either => Either::fromBrand($f($b))
        );
    }
}
