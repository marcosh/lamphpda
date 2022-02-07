<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

/**
 * @see https://github.com/marcosh/lamphpda/tree/master/docs/typeclasses/Semigroup.md
 *
 * @template-covariant A
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
