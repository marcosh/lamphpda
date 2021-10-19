<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Identity;

use Marcosh\LamPHPda\Brand\IdentityBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Identity;
use Marcosh\LamPHPda\Typeclass\Functor;

/**
 * @implements Functor<IdentityBrand>
 *
 * @psalm-immutable
 */
final class IdentityFunctor implements Functor
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
        return Identity::fromBrand($a)->eval(
            /**
             * @param A $value
             * @return Identity<B>
             */
            fn($value) => Identity::wrap($f($value))
        );
    }
}
