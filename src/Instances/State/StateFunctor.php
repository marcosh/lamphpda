<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\State;

use Marcosh\LamPHPda\Brand\StateBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Pair;
use Marcosh\LamPHPda\State;
use Marcosh\LamPHPda\Typeclass\Functor;

/**
 * @template S
 *
 * @implements Functor<StateBrand<S>>
 *
 * @psalm-immutable
 */
final class StateFunctor implements Functor
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
        $stateA = State::fromBrand($a);

        return State::state(
            /**
             * @param S $state
             * @return Pair<S, B>
             */
            static fn (mixed $state): Pair => $stateA->runState($state)->map($f)
        );
    }
}
