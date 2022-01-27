<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Pair;

use Marcosh\LamPHPda\Brand\PairBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Pair;
use Marcosh\LamPHPda\Typeclass\Apply;
use Marcosh\LamPHPda\Typeclass\Monoid;

/**
 * @template C
 *
 * @implements Apply<PairBrand<C>>
 *
 * @psalm-immutable
 */
final class PairApply implements Apply
{
    /** @var Monoid<C> */
    private Monoid $monoid;

    /**
     * @param Monoid<C> $monoid
     */
    public function __construct(Monoid $monoid)
    {
        $this->monoid = $monoid;
    }

    /**
     * @template A
     * @template B
     * @param callable(A): B $f
     * @param HK1<PairBrand<C>, A> $a
     * @return Pair<C, B>
     *
     * @psalm-pure
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    public function map(callable $f, HK1 $a): HK1
    {
        return (new PairFunctor())->map($f, $a);
    }

    /**
     * @template A
     * @template B
     * @param HK1<PairBrand<C>, callable(A): B> $f
     * @param HK1<PairBrand<C>, A> $a
     * @return Pair<C, B>
     *
     * @psalm-pure
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    public function apply(HK1 $f, HK1 $a): Pair
    {
        $pairF = Pair::fromBrand($f);
        $pairA = Pair::fromBrand($a);

        return $pairF->eval(
            /**
             * @param C $cf
             * @param callable(A): B $f
             * @return Pair<C, B>
             */
            fn ($cf, callable $f) => $pairA->eval(
                /**
                 * @param C $ca
                 * @param A $a
                 * @return Pair<C, B>
                 */
                fn ($ca, callable $a) => Pair::pair($this->monoid->append($cf, $ca), $f($a))
            )
        );
    }
}
