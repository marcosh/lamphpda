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
final class ConstantSemigroup implements Semigroup
{
    /**
     * @param A $c
     */
    public function __construct(private readonly $c)
    {
    }

    /**
     * @param A $a
     * @param A $b
     * @return A
     */
    public function append($a, $b)
    {
        return $this->c;
    }
}
