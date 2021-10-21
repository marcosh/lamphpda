<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

/**
 * @template A
 *
 * @psalm-immutable
 */
interface Monoid extends Semigroup
{
    /**
     * @return Monoid<A>
     *
     * @psalm-pure
     */
    public static function mempty(): Monoid;
}
