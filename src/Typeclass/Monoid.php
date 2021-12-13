<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

/**
 * @template-covariant A
 *
 * @extends Semigroup<A>
 *
 * @psalm-immutable
 */
interface Monoid extends Semigroup
{
    /**
     * @return A
     *
     * @psalm-pure
     */
    public function mempty();
}
