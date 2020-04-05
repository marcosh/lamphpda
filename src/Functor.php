<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

/**
 * @template A
 */
interface Functor
{
    /**
     * @template B
     * @param callable $f
     * @psalm-param callable(A): B $f
     * @return self
     * @psalm-return self&Functor<B>
     */
    public function map(callable $f): self;
}
