<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\IOBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Instances\IO\IOApplicative;
use Marcosh\LamPHPda\Instances\IO\IOApply;
use Marcosh\LamPHPda\Instances\IO\IOFunctor;
use Marcosh\LamPHPda\Instances\IO\IOMonad;
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Apply;
use Marcosh\LamPHPda\Typeclass\DefaultInstance\DefaultMonad;
use Marcosh\LamPHPda\Typeclass\Functor;
use Marcosh\LamPHPda\Typeclass\Monad;

/**
 * an instance of IO<A> represents a computation which will return an instance of type A
 *
 * @template-covariant A
 *
 * @implements DefaultMonad<IOBrand, A>
 *
 * @psalm-immutable
 */
final class IO implements DefaultMonad
{
    /** @var callable(): A */
    private $action;

    /**
     * @param callable(): A $action
     */
    private function __construct(callable $action)
    {
        $this->action = $action;
    }

    /**
     * @template B
     * @param callable(): B $action
     * @return IO<B>
     *
     * @psalm-pure
     */
    public static function action(callable $action): self
    {
        return new self($action);
    }

    /**
     * @template B
     * @param HK1<IOBrand, B> $hk
     * @return IO<B>
     *
     * @psalm-pure
     */
    public static function fromBrand(HK1 $hk): self
    {
        /** @var IO<B> */
        return $hk;
    }

    /**
     * @return A
     */
    public function eval()
    {
        /** @psalm-suppress ImpureFunctionCall */
        return ($this->action)();
    }

    /**
     * @template B
     * @param Functor<IOBrand> $functor
     * @param callable(A): B $f
     * @return IO<B>
     */
    public function imap(Functor $functor, callable $f): self
    {
        return self::fromBrand($functor->map($f, $this));
    }

    /**
     * @template B
     * @param callable(A): B $f
     * @return IO<B>
     */
    public function map(callable $f): self
    {
        return $this->imap(new IOFunctor(), $f);
    }

    /**
     * @template B
     * @param Apply<IOBrand> $apply
     * @param HK1<IOBrand, callable(A): B> $f
     * @return IO<B>
     */
    public function iapply(Apply $apply, HK1 $f): self
    {
        return self::fromBrand($apply->apply($f, $this));
    }

    /**
     * @template B
     * @param HK1<IOBrand, callable(A): B> $f
     * @return IO<B>
     */
    public function apply(HK1 $f): self
    {
        return $this->iapply(new IOApply(), $f);
    }

    /**
     * @template B
     * @param Applicative<IOBrand> $applicative
     * @param B $a
     * @return IO<B>
     *
     * @psalm-pure
     */
    public static function ipure(Applicative $applicative, $a): self
    {
        return self::fromBrand($applicative->pure($a));
    }

    /**
     * @template B
     * @param B $a
     * @return IO<B>
     *
     * @psalm-pure
     */
    public static function pure($a): self
    {
        return self::ipure(new IOApplicative(), $a);
    }

    /**
     * @template B
     * @param Monad<IOBrand> $monad
     * @param callable(A): HK1<IOBrand, B> $f
     * @return IO<B>
     */
    public function ibind(Monad $monad, callable $f): self
    {
        return self::fromBrand($monad->bind($this, $f));
    }

    /**
     * @template B
     * @param callable(A): HK1<IOBrand, B> $f
     * @return IO<B>
     */
    public function bind(callable $f): self
    {
        return $this->ibind(new IOMonad(), $f);
    }
}
