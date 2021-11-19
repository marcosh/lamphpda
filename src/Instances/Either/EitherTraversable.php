<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Either;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\Brand\EitherBrand;
use Marcosh\LamPHPda\Either;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Traversable;

/**
 * @implements Traversable<EitherBrand>
 *
 * @psalm-immutable
 */
final class EitherTraversable implements Traversable
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
        return (new EitherFunctor())->map($f, $a);
    }

    /**
     * @template A
     * @template B
     * @template C
     * @param pure-callable(A, B): B $f
     * @param B $b
     * @param HK1<EitherBrand<C>, A> $a
     * @return B
     *
     * @psalm-pure
     */
    public function foldr(callable $f, $b, HK1 $a)
    {
        return (new EitherFoldable())->foldr($f, $b, $a);
    }


    /**
     * @template F of Brand
     * @template A
     * @template B
     * @template C
     * @param Applicative<F> $applicative
     * @param callable(A): HK1<F, B> $f
     * @param HK1<EitherBrand<C>, A> $a
     * @return HK1<F, Either<C, B>>
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function traverse(Applicative $applicative, callable $f, HK1 $a): HK1
    {
        $eitherA = Either::fromBrand($a);

        return $eitherA->eval(
            /**
             * @param C $c
             * @return HK1<F, Either<C, B>>
             */
            function ($c) use ($applicative) {
                /** @var Either<C, B> $eitherCB */
                $eitherCB = Either::left($c);

                return $applicative->pure($eitherCB);
            },
            /**
             * @param A $a
             * @return HK1<F, Either<C, B>>
             *
             * @psalm-suppress InvalidArgument
             */
            fn($a) => $applicative->map([Either::class, 'right'], $f($a))
        );
    }
}
