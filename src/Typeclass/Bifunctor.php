<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK2Covariant;

/**
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
