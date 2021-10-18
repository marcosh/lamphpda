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
 * @extends DefaultApply<F, A>
 *
 * @psalm-immutable
 */
interface DefaultApplicative extends DefaultApply
{
    /**
     * @template B
     * @param B $a
     * @return HK1<F, B>
     */
    public static function pure($a): HK1;
}
