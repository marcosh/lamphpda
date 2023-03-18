<?php

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
     * @psalm-pure
     */
    public function mempty(): Traversable
    {
        return Traversable::fromArray([]);
    }

    /**
     * @template A
     * @param Traversable<A> $a
     * @param Traversable<A> $b
     * @return Traversable<A>
     *
     * @psalm-pure
     */
    public function append(mixed $a, mixed $b): Traversable
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
}
