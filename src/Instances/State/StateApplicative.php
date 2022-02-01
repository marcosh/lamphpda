<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\State;

use Marcosh\LamPHPda\Brand\StateBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Pair;
use Marcosh\LamPHPda\State;
use Marcosh\LamPHPda\Typeclass\Applicative;

/**
 * @template S
 *
 * @implements Applicative<StateBrand<S>>
 *
 * @psalm-immutable
 */
final class StateApplicative implements Applicative
{
    /**
     * @template A
     * @template B
     * @param callable(A): B $f
     * @param HK1<StateBrand<S>, A> $a
     * @return State<S, B>
     *
     * @psalm-pure
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    public function map(callable $f, HK1 $a): State
    {
        return (new StateFunctor())->map($f, $a);
    }

    /**
     * @template A
     * @template B
     * @param HK1<StateBrand<S>, callable(A): B> $f
     * @param HK1<StateBrand<S>, A> $a
     * @return State<S, B>
     *
     * @psalm-pure
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    public function apply(HK1 $f, HK1 $a): State
    {
        return (new StateApply())->apply($f, $a);
    }

    /**
     * @template A
     * @param A $a
     * @return State<S, A>
     *
     * @psalm-pure
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    public function pure($a): State
    {
        return State::state(static fn ($s) => Pair::pair($s, $a));
    }
}
