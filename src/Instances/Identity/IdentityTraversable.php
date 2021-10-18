<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Identity;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\Brand\IdentityBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Identity;
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Traversable;

/**
 * @implements Traversable<IdentityBrand>
 *
 * @psalm-immutable
 */
final class IdentityTraversable implements Traversable
{
    /**
     * @template A
     * @template B
     * @param callable(A): B $f
     * @param HK1<IdentityBrand, A> $a
     * @return Identity<B>
     *
     * @psalm-pure
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function map(callable $f, $a): Identity
    {
        return (new IdentityFunctor())->map($f, $a);
    }

    /**
     * @template A
     * @template B
     * @param callable(A, B): B $f
     * @param B $b
     * @param HK1<IdentityBrand, A> $a
     * @return B
     */
    public function foldr(callable $f, $b, HK1 $a)
    {
        return (new IdentityFoldable())->foldr($f, $b, $a);
    }

    /**
     * @template F of Brand
     * @template A
     * @template B
     * @param Applicative<F> $applicative
     * @param callable(A): HK1<F, B> $f
     * @param HK1<IdentityBrand, A> $a
     * @return HK1<F, Identity<B>>
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function traverse(Applicative $applicative, callable $f, HK1 $a): HK1
    {
        $identityA = Identity::fromBrand($a);

        return $identityA->eval(
            /**
             * @param A $a
             * @return HK1<F, Identity<B>>
             *
             * @psalm-suppress InvalidArgument
             */
            fn($a) => $applicative->map([Identity::class, 'of'], $f($a))
        );
    }
}
