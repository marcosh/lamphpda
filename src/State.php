<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

/**
 * @template A
 * @template S
 * @implements Functor<A>
 * @psalm-immutable
 */
final class State implements Functor
{
    /**
     * @var callable
     * @psalm-var callable(S): Pair<A,S>
     */
    private $runState;

    /**
     * @param callable $runState
     * @psalm-param callable(S): Pair<A,S> $runState
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
     * @psalm-param callable(T): Pair<B,T> $runState
     * @return self
     * @psalm-return self<B,T>
     * @psalm-pure
     */
    public static function state(callable $runState): self
    {
        return new self($runState);
    }

    /**
     * @param mixed $state
     * @psalm-param S $state
     * @return Pair
     * @psalm-return Pair<A,S>
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
     * @return self
     * @psalm-return self<B,S>
     * @psalm-pure
     */
    public function map(callable $f): self
    {
        $newRunState =
            /**
             * @psalm-param S $s
             * @psalm-return Pair<B,S>
             */
            fn($s) => $this->runState($s)->lmap($f);

        return self::state($newRunState);
    }
}
