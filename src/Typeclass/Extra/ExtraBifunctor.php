<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass\Extra;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK2Covariant;
use Marcosh\LamPHPda\Typeclass\Bifunctor;

/**
 * @template F of Brand
 *
 * @psalm-immutable
 */
final class ExtraBifunctor
{
    /** @var Bifunctor<F> */
    private Bifunctor $bifunctor;

    /**
     * @param Bifunctor<F> $bifunctor
     */
    public function __construct(Bifunctor $bifunctor)
    {
        $this->bifunctor = $bifunctor;
    }

    /**
     * @template A
     * @template B
     * @template C
     * @param callable(A): C $f
     * @param HK2Covariant<F, A, B> $hk2
     * @return HK2Covariant<F, C, B>
     */
    public function first(callable $f, HK2Covariant $hk2): HK2Covariant
    {
        return $this->bifunctor->biMap(
            $f,
            /**
             * @param B $b
             * @return B
             */
            fn($b) => $b,
            $hk2
        );
    }

    /**
     * @template A
     * @template B
     * @template C
     * @param callable(B): C $g
     * @param HK2Covariant<F, A, B> $hk2
     * @return HK2Covariant<F, A, C>
     */
    public function second(callable $g, HK2Covariant $hk2): HK2Covariant
    {
        return $this->bifunctor->biMap(
            /**
             * @param A $a
             * @return A
             */
            fn($a) => $a,
            $g,
            $hk2
        );
    }
}
