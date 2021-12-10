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
 * @extends DefaultApplicative<F, A>
 *
 * @psalm-immutable
 */
interface DefaultMonad extends DefaultApplicative
{
    /**
     * @template B
     *
     * @param callable(A): HK1<F, B> $f
     *
     * @return HK1<F, B>
     */
    public function bind(callable $f): HK1;
}
