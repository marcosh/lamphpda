<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK2;

/**
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
     *
     * @param pure-callable(A): B $f
     * @param pure-callable(C): D $g
     * @param HK2<F, B, C> $a
     *
     * @return HK2<F, A, D>
     */
    public function diMap(callable $f, callable $g, HK2 $a): HK2;
}
