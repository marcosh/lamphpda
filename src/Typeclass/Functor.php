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
     * @param callable $f
     * @psalm-param callable(A): B $f
     * @return self
     * @psalm-return self<F, B>
     */
    public function map(
        callable $f
    ): self;
}
