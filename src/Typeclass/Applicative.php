<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

/**
 * @template F
 * @template A
 * @extends Apply<F, A>
 */
interface Applicative extends Apply
{
    /**
     * @template B
     * @param mixed $a
     * @psalm-param B $a
     * @return Applicative
     * @psalm-return Applicative<F, B>
     */
    public static function pure($a): Applicative;
}
