<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass\Extra;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Traversable;

/**
 * @template T of Brand
 *
 * @psalm-immutable
 */
final class ExtraTraversable
{
    /**
     * @param Traversable<T> $traversable
     */
    public function __construct(private readonly Traversable $traversable)
    {
    }

    /**
     * @template F of Brand
     * @template A
     * @param Applicative<F> $applicative
     * @param HK1<T, HK1<F, A>> $hk
     * @return HK1<F, HK1<T, A>>
     */
    public function sequence($applicative, $hk)
    {
        return $this->traversable->traverse(
            $applicative,
            /**
             * @param HK1<F, A> $fa
             * @return HK1<F, A>
             */
            static fn (HK1 $fa): HK1 => $fa,
            $hk
        );
    }
}
