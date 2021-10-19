<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Identity;

use Marcosh\LamPHPda\Brand\IdentityBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Identity;
use Marcosh\LamPHPda\Typeclass\Functor;

/**
 * @psalm-immutable
 *
 * @implements Functor<IdentityBrand>
 */
final class IdentityFunctor implements Functor
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
        $identityA = Identity::fromBrand($a);

        return Identity::wrap($f($identityA->unwrap()));
    }
}
