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
