<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\StateBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Instances\State\StateApply;
use Marcosh\LamPHPda\Instances\State\StateFunctor;
use Marcosh\LamPHPda\Typeclass\Apply;
use Marcosh\LamPHPda\Typeclass\DefaultInstance\DefaultApply;
use Marcosh\LamPHPda\Typeclass\Functor;

/**
 * @template S
 * @template-covariant A
 *
 * @implements DefaultApply<StateBrand<S>, A>
 *
 * @psalm-immutable
 */
final class State implements DefaultApply
{
    /** @var callable(S): Pair<S, A> */
    private $runState;

    /**
     * @param callable(S): Pair<S, A> $runState
     */
    private function __construct(callable $runState)
    {
        $this->runState = $runState;
    }

    /**
     * @template T
     * @template B
     * @param callable(T): Pair<T, B> $runState
     * @return State<T, B>
     *
     * @psalm-pure
     */
    public static function state(callable $runState): self
    {
        return new self($runState);
    }

    /**
     * @template B
     * @template T
     * @param HK1<StateBrand<T>, B> $b
     * @return State<T, B>
     *
     * @psalm-pure
     */
    public static function fromBrand(HK1 $b): self
    {
        /** @var State<T, B> */
        return $b;
    }

    /**
     * @param S $state
     * @return Pair<S, A>
     */
    public function runState($state): Pair
    {
        /** @psalm-suppress ImpureFunctionCall */
        return ($this->runState)($state);
    }

    /**
     * @param S $state
     * @return A
     */
    public function evalState($state)
    {
        return $this->runState($state)->second();
    }

    /**
     * @param S $state
     * @return S
     */
    public function execState($state)
    {
        return $this->runState($state)->first();
    }

    /**
     * @template B
     * @param Functor<StateBrand<S>> $functor
     * @param callable(A): B $f
     * @return State<S, B>
     */
    public function imap(Functor $functor, callable $f): self
    {
        return self::fromBrand($functor->map($f, $this));
    }

    /**
     * @template B
     * @param callable(A): B $f
     * @return State<S, B>
     */
    public function map(callable $f): self
    {
        return $this->imap(new StateFunctor(), $f);
    }

    /**
     * @template B
     * @param Apply<StateBrand<S>> $apply
     * @param HK1<StateBrand<S>, callable(A): B> $f
     * @return State<S, B>
     */
    public function iapply(Apply $apply, HK1 $f): self
    {
        return self::fromBrand($apply->apply($f, $this));
    }

    /**
     * @template B
     * @param HK1<StateBrand<S>, callable(A): B> $f
     * @return State<S, B>
     */
    public function apply(HK1 $f): self
    {
        return $this->iapply(new StateApply(), $f);
    }
}
