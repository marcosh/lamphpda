<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

use Marcosh\LamPHPda\HK\HK0;

/**
 * @template T
 * @extends HK0<T>
 */
interface Semigroup extends HK0
{
    /**
     * @param HK0<T> $that
     * @return HK0<T>
     * @psalm-pure
     */
    public function append(HK0 $that): HK0;
}
