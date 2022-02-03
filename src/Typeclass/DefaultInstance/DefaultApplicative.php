<?php

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
     *
     * @psalm-pure
     */
    public static function pure($a): HK1;
}
