<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Traversable;

use Marcosh\LamPHPda\Traversable;
use Marcosh\LamPHPda\Typeclass\Monoid;

/**
 * @implements Monoid<Traversable>
 *
 * @psalm-immutable
 */
final class TraversableMonoid implements Monoid
{
    /**
     * @template A
     *
     * @param Traversable<A> $a
     * @param Traversable<A> $b
     *
     * @return Traversable<A>
     *
     * @psalm-pure
     */
    public function append($a, $b): Traversable
    {
        $ret = [];

        /** @psalm-suppress ImpureMethodCall */
        foreach ($a as $aElement) {
            $ret[] = $aElement;
        }

        /** @psalm-suppress ImpureMethodCall */
        foreach ($b as $bElement) {
            $ret[] = $bElement;
        }

        return Traversable::fromArray($ret);
    }

    /**
     * @psalm-pure
     */
    public function mempty(): Traversable
    {
        return Traversable::fromArray([]);
    }
}
