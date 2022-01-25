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
     * @psalm-mutation-free
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function map(callable $f, HK1 $a): Identity
    {
        $identityA = Identity::fromBrand($a);

        /** @psalm-suppress ImpureFunctionCall */
        return Identity::wrap($f($identityA->unwrap()));
    }
}
