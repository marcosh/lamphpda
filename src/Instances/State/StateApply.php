<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\State;

use Marcosh\LamPHPda\Brand\StateBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Pair;
use Marcosh\LamPHPda\State;
use Marcosh\LamPHPda\Typeclass\Apply;

/**
 * @template S
 *
 * @implements Apply<StateBrand<S>>
 *
 * @psalm-immutable
 */
final class StateApply implements Apply
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
        $stateF = State::fromBrand($f);
        $stateA = State::fromBrand($a);

        return State::state(
            /**
             * @param S $s
             * @return Pair<S, B>
             */
            static function ($s) use ($stateF, $stateA) {
                $fs = $stateF->runState($s);

                return $fs->eval(
                    /**
                     * @param S $s1
                     * @param callable(A): B $ff
                     * @return Pair<S, B>
                     */
                    static fn ($s1, callable $ff) => $stateA->runState($s1)->map(
                        /**
                         * @param A $aa
                         * @return B
                         */
                        static fn ($aa) => $ff($aa)
                    )
                );
            }
        );
    }
}
