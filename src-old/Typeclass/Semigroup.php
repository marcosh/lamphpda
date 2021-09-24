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
     * @param Semigroup<T> $that
     * @return Semigroup<T>
     * @psalm-pure
     */
    public function append(Semigroup $that): Semigroup;
}
