<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass\DefaultInstance;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK1;

/**
 * @template F of Brand
 * @template-covariant A
 *
 * @extends HK1<F, A>
 *
 * @psalm-immutable
 */
interface DefaultFunctor extends HK1
{
    /**
     * @template B
     * @param pure-callable(A): B $f
     * @return HK1<F, B>
     *
     * @psalm-mutation-free
     */
    public function map(callable $f): HK1;
}
