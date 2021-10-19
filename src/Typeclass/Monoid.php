<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

/**
 * @psalm-immutable
 *
 * @template A
 */
interface Monoid extends Semigroup
{
    /**
     * @psalm-pure
     *
     * @return Monoid<A>
     */
    public static function mempty(): Monoid;
}
