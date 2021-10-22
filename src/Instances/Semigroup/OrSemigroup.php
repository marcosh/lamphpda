<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Semigroup;

use Marcosh\LamPHPda\Typeclass\Semigroup;

/**
 * @template A
 *
 * @implements Semigroup<A>
 *
 * @psalm-immutable
 */
final class OrSemigroup implements Semigroup
{
    /**
     * @param A $a
     * @param A $b
     * @return A
     *
     * @psalm-pure
     */
    public function append($a, $b)
    {
        return $a || $b;
    }
}
