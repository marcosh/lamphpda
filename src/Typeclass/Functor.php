<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

use Marcosh\LamPHPda\HK\HK1;

/**
 * @template F
 * @template A
 * @extends HK1<F, A>
 */
interface Functor extends HK1
{
    /**
     * @template B
     * @param callable $f
     * @psalm-param callable(A): B $f
     * @return Functor
     * @psalm-return Functor<F, B>
     * @psalm-pure
     */
    public function map(
        callable $f
    ): Functor;
}
