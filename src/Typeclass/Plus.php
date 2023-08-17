<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK1;

/**
 * @see https://github.com/marcosh/lamphpda/tree/master/docs/typeclasses/Plus.md
 *
 * @template F of Brand
 *
 * @extends Alt<F>
 *
 * @psalm-immutable
 */
interface Plus extends Alt
{
    /**
     * @template A
     * @return HK1<F, A>
     */
    public function empty();
}
