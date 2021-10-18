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
 * @template F of Brand
 *
 * @extends Applicative<F>
 *
 * @psalm-immutable
 */
interface Monad extends Applicative
{
    /**
     * @template A
     * @template B
     * @param HK1<F, A> $a
     * @param callable(A): HK1<F, B> $f
     * @return HK1<F, B>
     */
    public function bind(HK1 $a, callable $f): HK1;
}
