<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK1;

/**
 * @see https://github.com/marcosh/lamphpda/tree/master/docs/typeclasses/Monad.md
 *
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
     *
     * @psalm-pure
     */
    public function bind(HK1 $a, callable $f): HK1;
}
