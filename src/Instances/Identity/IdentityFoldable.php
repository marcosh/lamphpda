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
use Marcosh\LamPHPda\Typeclass\Foldable;

/**
 * @implements Foldable<IdentityBrand>
 *
 * @psalm-immutable
 */
final class IdentityFoldable implements Foldable
{
    /**
     * @template A
     * @template B
     * @param pure-callable(A, B): B $f
     * @param B $b
     * @param HK1<IdentityBrand, A> $a
     * @return B
     */
    public function foldr(callable $f, $b, HK1 $a)
    {
        $identityA = Identity::fromBrand($a);

        return $f($identityA->unwrap(), $b);
    }
}
