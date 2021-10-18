<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass\DefaultInstance;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK2;

/**
 * @template F of Brand
 * @template A
 * @template-covariant B
 *
 * @extends HK2<F, A, B>
 *
 * @psalm-immutable
 */
interface DefaultProfunctor extends HK2
{
    /**
     * @template C
     * @template D
     * @param pure-callable(C): A $f
     * @param pure-callable(B): D $g
     * @return HK2<F, C, D>
     *
     * @psalm-mutation-free
     */
    public function diMap(callable $f, callable $g): HK2;
}
