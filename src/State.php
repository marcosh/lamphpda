<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\StateBrand;
use Marcosh\LamPHPda\HK\HK;
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Apply;
use Marcosh\LamPHPda\Typeclass\Functor;
use Marcosh\LamPHPda\Typeclass\Monad;

/**
 * @template A
 * @template S
 * @implements Functor<StateBrand, A>
 * @implements Apply<StateBrand, A>
 * @implements Applicative<StateBrand, A>
 * @implements Monad<StateBrand, A>
 * @psalm-immutable
 */
final class State implements Applicative, Apply, Functor, Monad
{
    /**
     * @var callable
     * @psalm-var callable(S): Pair<A, S>
     */
    private $runState;

    /**
     * @psalm-param callable(S): Pair<A, S> $runState
     * @psalm-pure
     */
    private function __construct(callable $runState)
    {
        $this->runState = $runState;
    }

    /**
     * @template B
     * @psalm-param Apply<StateBrand, callable(A): B> $f
     * @psalm-return State<B, S>
     * @psalm-pure
     */
    public function apply(Apply $f): State
    {
        $f = self::fromBrand($f);

        $newRunState =
            /**
             * @psalm-param S $s
             * @psalm-return Pair<B, S>
             *
             * @param mixed $s
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
     * @psalm-param callable(A): Monad<StateBrand, B> $f
     * @psalm-return State<B, S>
     * @psalm-pure
     */
    public function bind(callable $f): State
    {
        $newRunState =
            /**
             * @psalm-param S $s
             * @psalm-return Pair<B, S>
             */
            fn ($s) => $this->runState($s)->uncurry(
                /**
                 * @psalm-param A $a
                 * @psalm-param S $newS
                 * @psalm-return Pair<B, S>
                 */
                static fn ($a, $newS) => self::fromBrand($f($a))->runState($newS)
            );

        return self::state($newRunState);
    }

    /**
     * @template B
     * @psalm-param callable(A): B $f
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
            fn ($s) => $this->runState($s)->lmap($f);

        return self::state($newRunState);
    }

    /**
     * @template B
     * @template T
     *
     * @param mixed $a
     * @psalm-param B $a
     * @psalm-return State<B, T>
     * @psalm-pure
     */
    public static function pure($a): State
    {
        return self::state(
            (/**
             * @psalm-param T $s
             */
            static fn ($s) => Pair::pair($a, $s)
            )
        );
    }

    /**
     * @param mixed $state
     * @psalm-param S $state
     * @psalm-return Pair<A, S>
     * @psalm-pure
     */
    public function runState($state): Pair
    {
        return ($this->runState)($state);
    }

    /**
     * @template T
     * @template B
     * @psalm-param callable(T): Pair<B, T> $runState
     * @psalm-return State<B, T>
     * @psalm-pure
     */
    public static function state(callable $runState): State
    {
        return new self($runState);
    }

    /**
     * @template B
     * @psalm-param HK<StateBrand, B> $hk
     * @psalm-return State<B, S>
     * @psalm-pure
     */
    private static function fromBrand(HK $hk): State
    {
        /** @var State $hk */
        return $hk;
    }
}
