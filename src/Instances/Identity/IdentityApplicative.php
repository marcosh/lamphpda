<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Identity;

use Marcosh\LamPHPda\Brand\IdentityBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Identity;
use Marcosh\LamPHPda\Typeclass\Applicative;

/**
 * @psalm-immutable
 *
 * @implements Applicative<IdentityBrand>
 */
final class IdentityApplicative implements Applicative
{
    /**
     * @psalm-suppress LessSpecificImplementedReturnType
     *
     * @psalm-pure
     *
     * @template A
     * @template B
     *
     * @param callable(A): B $f
     * @param HK1<IdentityBrand, A> $a
     *
     * @return Identity<B>
     */
    public function map(callable $f, $a): Identity
    {
        return (new IdentityFunctor())->map($f, $a);
    }

    /**
     * @psalm-suppress LessSpecificImplementedReturnType
     *
     * @psalm-pure
     *
     * @template A
     * @template B
     *
     * @param HK1<IdentityBrand, callable(A): B> $f
     * @param HK1<IdentityBrand, A> $a
     *
     * @return Identity<B>
     */
    public function apply(HK1 $f, HK1 $a): Identity
    {
        return (new IdentityApply())->apply($f, $a);
    }

    /**
     * @psalm-suppress LessSpecificImplementedReturnType
     *
     * @psalm-pure
     *
     * @template A
     *
     * @param A $a
     *
     * @return Identity<A>
     */
    public function pure($a): Identity
    {
        return Identity::wrap($a);
    }
}
