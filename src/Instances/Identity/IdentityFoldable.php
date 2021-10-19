<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Identity;

use Marcosh\LamPHPda\Brand\IdentityBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Identity;
use Marcosh\LamPHPda\Typeclass\Foldable;

/**
 * @psalm-immutable
 *
 * @implements Foldable<IdentityBrand>
 */
final class IdentityFoldable implements Foldable
{
    /**
     * @template A
     * @template B
     *
     * @param callable(A, B): B $f
     * @param B $b
     * @param HK1<IdentityBrand, A> $a
     *
     * @return B
     */
    public function foldr(callable $f, $b, HK1 $a)
    {
        $identityA = Identity::fromBrand($a);

        return $f($identityA->unwrap(), $b);
    }
}
