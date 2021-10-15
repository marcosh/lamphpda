<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

/**
 * @template A
 *
 * @psalm-immutable
 */
interface Semigroup
{
    /**
     * @param A $a
     * @param A $b
     * @return A
     *
     * @psalm-pure
     */
    public function append($a, $b);
}
