<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK1;

/**
 * @see https://github.com/marcosh/lamphpda/tree/master/docs/typeclasses/Alternative.md
 *
 * @template F of Brand
 *
 * @extends Applicative<F>
 *
 * @psalm-immutable
 */
interface Alternative extends Applicative
{
    /**
     * @template A
     * @return HK1<F, A>
     */
    public function empty();

    /**
     * @template A
     * @param HK1<F, A> $a
     * @param HK1<F, A> $b
     * @return HK1<F, A>
     */
    public function alt(HK1 $a, HK1 $b): HK1;
}
