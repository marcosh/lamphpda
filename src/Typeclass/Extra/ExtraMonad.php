<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass\Extra;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Typeclass\Monad;

/**
 * @template F of Brand
 *
 * @psalm-immutable
 */
final class ExtraMonad
{
    /** @var Monad<F> */
    private Monad $monad;

    /**
     * @param Monad<F> $monad
     */
    private function __construct(Monad $monad)
    {
        $this->monad = $monad;
    }

    /**
     * @template A
     * @param HK1<F, HK1<F, A>> $hk
     * @return HK1<F, A>
     */
    public function join(HK1 $hk): HK1
    {
        return $this->monad->bind(
            $hk,
            /**
             * @param HK1<F, A> $hka
             * @return HK1<F, A>
             */
            static fn (HK1 $hka) => $hka
        );
    }
}
