<?php

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
final class OppositeSemigroup implements Semigroup
{
    /**
     * @param Semigroup<A> $semigroup
     */
    public function __construct(private readonly Semigroup $semigroup)
    {
    }

    /**
     * @param A $a
     * @param A $b
     * @return A
     */
    public function append($a, $b): mixed
    {
        return $this->semigroup->append($b, $a);
    }
}
