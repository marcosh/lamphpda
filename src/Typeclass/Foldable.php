<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK1;

/**
 * @see https://github.com/marcosh/lamphpda/tree/master/docs/typeclasses/Foldable.md
 *
 * @template T of Brand
 *
 * @psalm-immutable
 */
interface Foldable
{
    /**
     * @template A
     * @template B
     * @param callable(A, B): B $f
     * @param B $b
     * @param HK1<T, A> $a
     * @return B
     */
    public function foldr(callable $f, mixed $b, HK1 $a): mixed;
}
