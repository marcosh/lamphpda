<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Maybe;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\Brand\MaybeBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Maybe;
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Traversable;

/**
 * @implements Traversable<MaybeBrand>
 *
 * @psalm-immutable
 */
final class MaybeTraversable implements Traversable
{
    // TODO: remove this duplication

    /**
     * @template A
     * @template B
     * @param callable(A): B $f
     * @param HK1<MaybeBrand, A> $a
     * @return Maybe<B>
     *
     * @psalm-pure
     */
    public function map(callable $f, $a): Maybe
    {
        return Maybe::fromBrand($a)->eval(
            Maybe::nothing(),
            /**
             * @param A $value
             * @return Maybe<B>
             */
            fn($value) => Maybe::just($f($value))
        );
    }

    /**
     * @template A
     * @template B
     * @param callable(A, B): B $f
     * @param B $b
     * @param HK1<MaybeBrand, A> $a
     * @return B
     */
    public function foldr(callable $f, $b, HK1 $a)
    {
        $maybeA = Maybe::fromBrand($a);

        return $maybeA->eval(
            $b,
            /**
             * @param A $a
             * @return B
             */
            fn ($a) => $f($a, $b)
        );
    }

    /**
     * @template F of Brand
     * @template A
     * @template B
     * @param Applicative<F> $applicative
     * @param callable(A): HK1<F, B> $f
     * @param HK1<MaybeBrand, A> $a
     * @return HK1<F, Maybe<B>>
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    public function traverse(Applicative $applicative, callable $f, HK1 $a): HK1
    {
        $maybeA = Maybe::fromBrand($a);

        /** @var HK1<F, Maybe<B>> $onNothing */
        $onNothing = $applicative->pure(Maybe::nothing());

        return $maybeA->eval(
            $onNothing,
            /**
             * @param A $a
             * @return HK1<F, Maybe<B>>
             *
             * @psalm-suppress InvalidArgument
             */
            fn($a) => $applicative->map([Maybe::class, 'just'], $f($a))
        );
    }
}
