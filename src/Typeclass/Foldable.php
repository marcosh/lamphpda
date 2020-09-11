<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

/**
 * @template F
 * @template A
 * @extends HK1<F, A>
 */
interface Foldable
{
    /**
     * @template B
     * @param callable $op
     * @psalm-param callable(A, B): B $op
     * @param mixed $unit
     * @psalm-param B $unit
     * @return mixed
     * @psalm-return B
     */
    public function foldr(callable $op, $unit);
}
