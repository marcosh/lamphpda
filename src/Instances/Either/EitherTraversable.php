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
 * @template C
 *
 * @implements Traversable<EitherBrand<C>>
 *
 * @psalm-immutable
 */
final class EitherTraversable implements Traversable
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
     * @param callable(A, B): B $f
     * @param B $b
     * @param HK1<EitherBrand<C>, A> $a
     * @return B
     *
     * @psalm-pure
     */
    public function foldr(callable $f, mixed $b, HK1 $a): mixed
    {
        return (new EitherFoldable())->foldr($f, $b, $a);
    }

    /**
     * @template F of Brand
     * @template A
     * @template B
     * @param Applicative<F> $applicative
     * @param callable(A): HK1<F, B> $f
     * @param HK1<EitherBrand<C>, A> $a
     * @return HK1<F, Either<C, B>>
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function traverse(Applicative $applicative, callable $f, HK1 $a): HK1
    {
        $eitherA = Either::fromBrand($a);

        /** @var callable(B): Either<C, B> $right */
        $right = [Either::class, 'right'];

        return $eitherA->eval(
            /**
             * @param C $c
             * @return HK1<F, Either<C, B>>
             */
            static function (mixed $c) use ($applicative): HK1 {
                /** @var Either<C, B> $eitherCB */
                $eitherCB = Either::left($c);

                return $applicative->pure($eitherCB);
            },
            /**
             * @param A $a
             * @return HK1<F, Either<C, B>>
             */
            static fn (mixed $a): HK1 => $applicative->map($right, $f($a))
        );
    }
}
