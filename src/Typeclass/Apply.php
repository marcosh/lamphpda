<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK1;

/**
 * @template F of Brand
 * @extends Functor<F>
 *
 * @psalm-immutable
 */
interface Apply extends Functor
{
    /**
     * @template A
     * @template B
     * @param HK1<F, callable(A): B> $f
     * @param HK1<F, A> $a
     * @return HK1<F, B>
     *
     * @psalm-pure
     */
    public function apply(HK1 $f, HK1 $a): HK1;
}
