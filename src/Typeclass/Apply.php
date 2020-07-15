<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

/**
 * @template F
 * @template A
 * @extends Functor<F, A>
 */
interface Apply extends Functor
{
    /**
     * @template B
     * @psalm-param Apply<F, callable(A): B> $f
     * @psalm-return Apply<F, B>
     * @psalm-pure
     */
    public function apply(Apply $f): Apply;
}
