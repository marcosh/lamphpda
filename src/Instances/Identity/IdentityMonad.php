<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Identity;

use Marcosh\LamPHPda\Brand\IdentityBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Identity;
use Marcosh\LamPHPda\Typeclass\Monad;

/**
 * @implements Monad<IdentityBrand>
 *
 * @psalm-immutable
 */
final class IdentityMonad implements Monad
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
    public function map(callable $f, HK1 $a): Identity
    {
        return (new IdentityFunctor())->map($f, $a);
    }

    /**
     * @template A
     * @template B
     * @param HK1<IdentityBrand, callable(A): B> $f
     * @param HK1<IdentityBrand, A> $a
     * @return Identity<B>
     *
     * @psalm-pure
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function apply(HK1 $f, HK1 $a): Identity
    {
        return (new IdentityApply())->apply($f, $a);
    }

    /**
     * @template A
     * @param A $a
     * @return Identity<A>
     *
     * @psalm-pure
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function pure(mixed $a): Identity
    {
        return (new IdentityApplicative())->pure($a);
    }

    /**
     * @template A
     * @template B
     * @param HK1<IdentityBrand, A> $a
     * @param callable(A): HK1<IdentityBrand, B> $f
     * @return Identity<B>
     *
     * @psalm-mutation-free
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function bind(HK1 $a, callable $f): Identity
    {
        $identityA = Identity::fromBrand($a);

        /** @psalm-suppress ImpureFunctionCall */
        return Identity::fromBrand($f($identityA->unwrap()));
    }
}
