<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

/**
 * @template A
 */
interface Apply extends Functor
{
    /**
     * @template B
     * @param self $f
     * @psalm-param self&Apply<callable(A): B> $f
     * @return self
     * @psalm-return self&Apply<B>
     */
    public function apply(Apply $f): Apply;
}
