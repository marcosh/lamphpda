<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Identity;

use Marcosh\LamPHPda\Brand\IdentityBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Identity;
use Marcosh\LamPHPda\Typeclass\Apply;

/**
 * @implements Apply<IdentityBrand>
 *
 * @psalm-immutable
 */
final class IdentityApply implements Apply
{
    /**
     * @template A
     * @template B
     * @param pure-callable(A): B $f
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
     * @psalm-mutation-free
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function apply(HK1 $f, HK1 $a): Identity
    {
        $identityF = Identity::fromBrand($f);
        $identityA = Identity::fromBrand($a);

        return Identity::wrap(($identityF->unwrap())($identityA->unwrap()));
    }
}
