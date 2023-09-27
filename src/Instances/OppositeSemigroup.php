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
    /** @var Semigroup<A> */
    private Semigroup $semigroup;

    /**
     * @param Semigroup<A> $semigroup
     */
    public function __construct(Semigroup $semigroup)
    {
        $this->semigroup = $semigroup;
    }

    /**
     * @param A $a
     * @param A $b
     * @return A
     */
    public function append($a, $b)
    {
        return $this->semigroup->append($b, $a);
    }
}
