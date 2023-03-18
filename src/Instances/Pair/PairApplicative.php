<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Pair;

use Marcosh\LamPHPda\Brand\PairBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Pair;
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Monoid;

/**
 * @template C
 *
 * @implements Applicative<PairBrand<C>>
 *
 * @psalm-immutable
 */
final class PairApplicative implements Applicative
{
    /**
     * @param Monoid<C> $monoid
     */
    public function __construct(private readonly Monoid $monoid)
    {
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
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    public function apply(HK1 $f, HK1 $a): Pair
    {
        return (new PairApply($this->monoid))->apply($f, $a);
    }

    /**
     * @template A
     * @param A $a
     * @return Pair<C, A>
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    public function pure(mixed $a): Pair
    {
        return Pair::pair($this->monoid->mempty(), $a);
    }
}
