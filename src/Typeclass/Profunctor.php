<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK2;

/**
 * @see https://github.com/marcosh/lamphpda/tree/master/docs/typeclasses/Profunctor.md
 *
 * @template F of Brand
 *
 * @psalm-immutable
 */
interface Profunctor
{
    /**
     * @template A
     * @template B
     * @template C
     * @template D
     * @param callable(A): B $f
     * @param callable(C): D $g
     * @param HK2<F, B, C> $a
     * @return HK2<F, A, D>
     */
    public function diMap(callable $f, callable $g, HK2 $a): HK2;
}
