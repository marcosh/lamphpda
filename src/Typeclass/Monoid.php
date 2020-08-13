<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

/**
 * @template T
 * @extends Semigroup<T>
 */
interface Monoid extends Semigroup
{
    /**
     * @return Monoid<T>
     * @psalm-pure
     */
    public static function mempty(): Monoid;
}
