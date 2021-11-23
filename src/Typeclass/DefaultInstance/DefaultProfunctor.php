<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass\DefaultInstance;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK2;

/**
 * @template F of Brand
 * @template A
 * @template B
 *
 * @extends HK2<F, A, B>
 */
interface DefaultProfunctor extends HK2
{
    /**
     * @template C
     * @template D
     * @param callable(C): A $f
     * @param callable(B): D $g
     * @return HK2<F, C, D>
     *
     * @psalm-mutation-free
     */
    public function diMap(callable $f, callable $g): HK2;
}
