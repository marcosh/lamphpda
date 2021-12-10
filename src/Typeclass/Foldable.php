<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK1;

/**
 * @template T of Brand
 *
 * @psalm-immutable
 */
interface Foldable
{
    /**
     * @template A
     * @template B
     *
     * @param pure-callable(A, B): B $f
     * @param B $b
     * @param HK1<T, A> $a
     *
     * @return B
     */
    public function foldr(callable $f, $b, HK1 $a);
}
