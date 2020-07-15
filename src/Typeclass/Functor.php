<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

use Marcosh\LamPHPda\HK\HK;

/**
 * @template F
 * @template A
 * @extends HK<F, A>
 */
interface Functor extends HK
{
    /**
     * @template B
     * @psalm-param callable(A): B $f
     * @psalm-return Functor<F, B>
     * @psalm-pure
     */
    public function map(
        callable $f
    ): Functor;
}
