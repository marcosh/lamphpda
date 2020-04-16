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
     * @param self $f
     * @psalm-param self<F, callable(A): B> $f
     * @return self
     * @psalm-return self<F, B>
     */
    public function apply(self $f): self;
}
