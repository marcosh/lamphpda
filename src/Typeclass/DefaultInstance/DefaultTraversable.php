<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass\DefaultInstance;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Typeclass\Applicative;

/**
 * @template T of Brand
 * @template-covariant A
 *
 * @extends DefaultFunctor<T, A>
 * @extends DefaultFoldable<T, A>
 *
 * @psalm-immutable
 */
interface DefaultTraversable extends DefaultFunctor, DefaultFoldable
{
    /**
     * @template F of Brand
     * @template B
     * @param Applicative<F> $applicative
     * @param callable(A): HK1<F, B> $f
     * @return HK1<F, HK1<T, B>>
     */
    public function traverse(Applicative $applicative, callable $f): HK1;
}
