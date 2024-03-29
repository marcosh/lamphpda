<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK1;

/**
 * @see https://github.com/marcosh/lamphpda/tree/master/docs/typeclasses/Traversable.md
 *
 * @template T of Brand
 *
 * @extends Functor<T>
 * @extends Foldable<T>
 *
 * @psalm-immutable
 */
interface Traversable extends Functor, Foldable
{
    /**
     * @template F of Brand
     * @template A
     * @template B
     * @param Applicative<F> $applicative
     * @param callable(A): HK1<F, B> $f
     * @param HK1<T, A> $a
     * @return HK1<F, HK1<T, B>>
     */
    public function traverse(Applicative $applicative, callable $f, HK1 $a): HK1;
}
