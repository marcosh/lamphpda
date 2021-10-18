<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances;

use Marcosh\LamPHPda\Typeclass\Semigroup;

/**
 * @template A
 *
 * @implements Semigroup<A>
 *
 * @psalm-immutable
 */
final class FirstSemigroup implements Semigroup
{
    /**
     * @param A $a
     * @param A $b
     * @return A
     */
    public function append($a, $b)
    {
        return $a;
    }
}
