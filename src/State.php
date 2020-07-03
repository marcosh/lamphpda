<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\StateBrand;
use Marcosh\LamPHPda\HK\HK;
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Apply;
use Marcosh\LamPHPda\Typeclass\Functor;

/**
 * @template A
 * @template S
 * @implements Functor<StateBrand, A>
 * @implements Apply<StateBrand, A>
 * @implements Applicative<StateBrand, A>
 * @psalm-immutable
 */
final class State implements Functor, Apply, Applicative
{
    /**
     * @var callable
     * @psalm-var callable(S): Pair<A, S>
     */
    private $runState;

    /**
     * @param callable $runState
     * @psalm-param callable(S): Pair<A, S> $runState
     * @psalm-pure
     */
    private function __construct(callable $runState)
    {
        $this->runState = $runState;
    }

    /**
     * @template T
     * @template B
     * @param callable $runState
     * @psalm-param callable(T): Pair<B, T> $runState
     * @return State
     * @psalm-return State<B, T>
     * @psalm-pure
     */
    public static function state(callable $runState): State
    {
        return new self($runState);
    }

    /**
     * @template B
     * @param HK $hk
     * @psalm-param HK<StateBrand, B> $hk
     * @return State
     * @psalm-return State<B, S>
     * @psalm-pure
     */
    private static function fromBrand(HK $hk): State
    {
        /** @var State $hk */
        return $hk;
    }

    /**
     * @param mixed $state
     * @psalm-param S $state
     * @return Pair
     * @psalm-return Pair<A, S>
     * @psalm-pure
     */
    public function runState($state): Pair
    {
        return ($this->runState)($state);
    }

    /**
     * @template B
     * @param callable $f
     * @psalm-param callable(A): B $f
     * @return State
     * @psalm-return State<B, S>
     * @psalm-pure
     */
    public function map(callable $f): State
    {
        $newRunState =
            /**
             * @psalm-param S $s
             * @psalm-return Pair<B, S>
             */
            fn($s) => $this->runState($s)->lmap($f);

        return self::state($newRunState);
    }

    /**
     * @template B
     * @param Apply $f
     * @psalm-param Apply<StateBrand, callable(A): B> $f
     * @return State
     * @psalm-return State<B, S>
     * @psalm-pure
     */
    public function apply(Apply $f): Apply
    {
        $f = self::fromBrand($f);

        $newRunState =
            /**
             * @psalm-param S $s
             * @psalm-return Pair<B, S>
             */
            function ($s) use ($f) {
                $callableAndState = $f->runState($s);

                return $callableAndState->uncurry(function ($callable, $state) {
                    $valueAndState = $this->runState($state);

                    return $valueAndState->lmap($callable);
                });
            };

        return self::state($newRunState);
    }

    /**
     * @template B
     * @template T
     * @param mixed $a
     * @psalm-param B $a
     * @return State
     * @psalm-return State<B, T>
     * @psalm-pure
     */
    public static function pure($a): State
    {
        return self::state(
            (/**
             * @psalm-param T $s
             */
            fn($s) => Pair::pair($a, $s))
        );
    }
}
