<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass\Extra;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Typeclass\Functor;

/**
 * @template F of Brand
 *
 * @psalm-immutable
 */
final class ExtraFunctor
{
    /** @var Functor<F> */
    private Functor $functor;

    /**
     * @param Functor<F> $functor
     */
    public function __construct(Functor $functor)
    {
        $this->functor = $functor;
    }

    /**
     * @template A
     * @template B
     * @param A $a
     * @param HK1<F, B> $hk
     * @return HK1<F, A>
     */
    public function voidRight($a, HK1 $hk): HK1
    {
        return $this->functor->map(
            static fn ($_) => $a,
            $hk
        );
    }
}
