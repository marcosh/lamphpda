<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances;

use Marcosh\LamPHPda\Typeclass\Monoid;

/**
 * @template A
 *
 * @implements Monoid<A>
 *
 * @psalm-immutable
 */
final class OppositeMonoid implements Monoid
{
    /** @var Monoid<A> */
    private Monoid $monoid;

    /**
     * @param Monoid<A> $monoid
     */
    public function __construct(Monoid $monoid)
    {
        $this->monoid = $monoid;
    }

    /**
     * @return A
     */
    public function mempty()
    {
        return $this->monoid->mempty();
    }

    /**
     * @param A $a
     * @param A $b
     * @return A
     */
    public function append($a, $b)
    {
        return (new OppositeSemigroup($this->monoid))->append($a, $b);
    }
}
