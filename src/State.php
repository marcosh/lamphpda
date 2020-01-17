<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

/**
 * @template S
 * @template A
 */
final class State
{
    /**
     * @var callable
     * @psalm-var callable(S): Pair<A,S>
     */
    private $runState;

    /**
     * @param callable $runState
     * @psalm-param callable(S): Pair<A,S> $runState
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
     * @return self<B,T>
     */
    public static function state(callable $runState): self
    {
        return new self($runState);
    }

    /**
     * @param mixed $state
     * @psalm-param S $state
     * @return Pair
     * @psalm-return Pair<A, S>
     */
    public function runState($state): Pair
    {
        return ($this->runState)($state);
    }
}
