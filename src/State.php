<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\StateBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Instances\State\StateApplicative;
use Marcosh\LamPHPda\Instances\State\StateApply;
use Marcosh\LamPHPda\Instances\State\StateFunctor;
use Marcosh\LamPHPda\Instances\State\StateMonad;
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Apply;
use Marcosh\LamPHPda\Typeclass\DefaultInstance\DefaultMonad;
use Marcosh\LamPHPda\Typeclass\Functor;
use Marcosh\LamPHPda\Typeclass\Monad;

/**
 * @template S
 * @template-covariant A
 *
 * @implements DefaultMonad<StateBrand<S>, A>
 *
 * @psalm-immutable
 */
final class State implements DefaultMonad
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

    /**
     * @template B
     * @template T
     * @param Applicative<StateBrand<T>> $applicative
     * @param B $a
     * @return State<T, B>
     *
     * @psalm-pure
     */
    public static function ipure(Applicative $applicative, $a): self
    {
        return self::fromBrand($applicative->pure($a));
    }

    /**
     * @template B
     * @template T
     * @param B $a
     * @return State<T, B>
     *
     * @psalm-pure
     */
    public static function pure($a): self
    {
        return self::ipure(new StateApplicative(), $a);
    }

    /**
     * @template B
     * @param Monad<StateBrand<S>> $monad
     * @param callable(A): HK1<StateBrand<S>, B> $f
     * @return State<S, B>
     */
    public function ibind(Monad $monad, callable $f): self
    {
        return self::fromBrand($monad->bind($this, $f));
    }

    /**
     * @template B
     * @param callable(A): HK1<StateBrand<S>, B> $f
     * @return State<S, B>
     */
    public function bind(callable $f): self
    {
        return $this->ibind(new StateMonad(), $f);
    }
}
