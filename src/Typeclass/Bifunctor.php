<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK2Covariant;

/**
 * @see https://github.com/marcosh/lamphpda/tree/master/docs/typeclasses/Bifunctor.md
 *
 * @template F of Brand
 *
 * @psalm-immutable
 */
interface Bifunctor
{
    /**
     * @template A
     * @template B
     * @template C
     * @template D
     * @param callable(A): C $f
     * @param callable(B): D $g
     * @param HK2Covariant<F, A, B> $a
     * @return HK2Covariant<F, C, D>
     */
    public function biMap(callable $f, callable $g, HK2Covariant $a): HK2Covariant;
}
