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
     * @psalm-param callable(A): Monad<F, B> $f
     * @psalm-return Monad<F, B>
     * @psalm-pure
     */
    public function bind(callable $f): Monad;
}
