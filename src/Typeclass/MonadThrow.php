<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK1;

/**
 * @template F of Brand
 * @template E
 * @extends Monad<F>
 *
 * @psalm-immutable
 */
interface MonadThrow extends Monad
{
    /**
     * @template A
     * @param E $e
     * @return HK1<F, A>
     */
    public function throwError($e): HK1;
}
