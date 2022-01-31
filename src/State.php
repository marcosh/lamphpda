<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

/**
 * @template S
 * @template-covariant A
 *
 * @psalm-immutable
 */
final class State
{
    /** @var callable(S): Pair<A, S> */
    private $runState;

    /**
     * @param callable(S): Pair<A, S> $runState
     */
    private function __construct(callable $runState)
    {
        $this->runState = $runState;
    }

    /**
     * @template T
     * @template B
     * @param callable(T): Pair<B, T> $runState
     * @return State<T, B>
     *
     * @psalm-pure
     */
    public static function state(callable $runState): State
    {
        return new self($runState);
    }

    /**
     * @param S $state
     * @return Pair<A, S>
     */
    public function runState($state): Pair
    {
        /** @psalm-suppress ImpureFunctionCall */
        return ($this->runState)($state);
    }
}
