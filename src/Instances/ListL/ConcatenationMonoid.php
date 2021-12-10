<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\ListL;

use Marcosh\LamPHPda\Typeclass\Monoid;

/**
 * @template A
 * @implements Monoid<list<A>>
 *
 * @psalm-immutable
 */
final class ConcatenationMonoid implements Monoid
{
    /**
     * @param list<A> $a
     * @param list<A> $b
     *
     * @return list<A>
     */
    public function append($a, $b)
    {
        return array_merge($b, $a);
    }

    /**
     * @return list<A>
     */
    public function mempty(): array
    {
        return [];
    }
}
