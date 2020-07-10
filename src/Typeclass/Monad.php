<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

/**
 * @template F
 * @template A
 * @extends Applicative<F, A>
 */
interface Monad extends Applicative
{
    /**
     * @template B
     * @param callable $f
     * @psalm-param callable(A): Monad<F, B> $f
     * @return Monad
     * @psalm-return Monad<F, B>
     * @psalm-pure
     */
    public function bind(callable $f): Monad;
}
