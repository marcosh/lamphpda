<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass\Extra;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK2;
use Marcosh\LamPHPda\Typeclass\Profunctor;

/**
 * @template F of Brand
 *
 * @psalm-immutable
 */
final class ExtraProfunctor
{
    /** @var Profunctor<F> */
    private Profunctor $profunctor;

    /**
     * @param Profunctor<F> $profunctor
     */
    public function __construct(Profunctor $profunctor)
    {
        $this->profunctor = $profunctor;
    }

    /**
     * @template A
     * @template B
     * @template C
     * @param pure-callable(A): B $f
     * @param HK2<F, C, A> $hk
     * @return HK2<F, C, B>
     */
    public function rmap(callable $f, HK2 $hk): HK2
    {
        return $this->profunctor->diMap(
            /**
             * @param C $c
             * @return C
             */
            fn($c) => $c,
            $f,
            $hk
        );
    }

    /**
     * @template A
     * @template B
     * @template C
     * @param pure-callable(A): B $f
     * @param HK2<F, B, C> $hk
     * @return HK2<F, A, C>
     */
    public function lmap(callable $f, HK2 $hk): HK2
    {
        return $this->profunctor->diMap(
            $f,
            /**
             * @param C $c
             * @return C
             */
            fn($c) => $c,
            $hk
        );
    }
}
