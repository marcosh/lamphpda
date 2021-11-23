<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass\DefaultInstance;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK1;

/**
 * @template F of Brand
 * @template A
 *
 * @extends DefaultApplicative<F, A>
 */
interface DefaultMonad extends DefaultApplicative
{
    /**
     * @template B
     * @param callable(A): HK1<F, B> $f
     * @return HK1<F, B>
     */
    public function bind(callable $f): HK1;
}
