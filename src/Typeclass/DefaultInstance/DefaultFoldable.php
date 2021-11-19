<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass\DefaultInstance;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK1;

/**
 * @template T of Brand
 * @template A
 * @extends HK1<T, A>
 */
interface DefaultFoldable extends HK1
{
    /**
     * @template B
     * @param pure-callable(A, B): B $f
     * @param B $b
     * @return B
     */
    public function foldr(callable $f, $b);
}
