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
 * @extends Apply<F>
 *
 * @psalm-immutable
 */
interface Applicative extends Apply
{
    /**
     * @template A
     *
     * @param A $a
     *
     * @return HK1<F, A>
     *
     * @psalm-pure
     */
    public function pure($a): HK1;
}
