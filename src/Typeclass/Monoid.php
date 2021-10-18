<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

/**
 * @template A
 *
 * @extends Semigroup<A>
 *
 * @psalm-immutable
 */
interface Monoid extends Semigroup
{
    /**
     * @return A
     *
     * @psalm-pure
     */
    public function mempty();
}
