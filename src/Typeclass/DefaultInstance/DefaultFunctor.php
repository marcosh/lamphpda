<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass\DefaultInstance;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK1;

/**
 * @template F of Brand
 * @template A
 * @extends HK1<F, A>
 */
interface DefaultFunctor extends HK1
{
    /**
     * @template B
     * @param callable(A): B $f
     * @return HK1<F, B>
     *
     * @psalm-mutation-free
     */
    public function map(callable $f);
}
