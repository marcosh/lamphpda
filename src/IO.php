<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

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
use Marcosh\LamPHPda\Brand\IOBrand;

/**
 * @template A
 *
 * @implements DefaultMonad<IOBrand, A>
 */
final class IO implements DefaultMonad
{
    /**
     * @var callable(): A
     */
    private $unsafe;

    /**
     * @param callable(): A $f
     */
    private function __construct(callable $f)
    {
        $this->unsafe = $f;
    }

    /**
     * @template B
     * @param B $x
     * @return IO<B>
     *
     * @psalm-pure
     */
    public static function of($x): IO
    {
        return new self(static fn () => $x);
    }

    /**
     * @template B
     * @param HK1<IOBrand, B> $b
     * @return IO<B>
     *
     * @psalm-pure
     */
    public static function fromBrand(HK1 $b): IO
    {
        /** @var IO $b */
        return $b;
    }

    /**
     * @return A
     *
     * @psalm-mutation-free
     */
    public function eval()
    {
        return ($this->unsafe)();
    }

    /**
     * @template B
     * @param Functor<IOBrand> $functor
     * @param callable(A): B $f
     * @return IO<B>
     *
     * @psalm-mutation-free
     */
    public function imap(Functor $functor, callable $f): IO
    {
        return self::fromBrand($functor->map($f, $this));
    }

    /**
     * @template B
     * @param callable(A): B $f
     * @return IO<B>
     *
     * @psalm-mutation-free
     */
    public function map(callable $f): IO
    {
        return $this->imap(new IOFunctor(), $f);
    }

    /**
     * @template B
     * @param Apply<IOBrand> $apply
     * @param HK1<IOBrand, callable(A): B> $f
     * @return IO<B>
     *
     * @psalm-mutation-free
     */
    public function iapply(Apply $apply, HK1 $f): IO
    {
        return self::fromBrand($apply->apply($f, $this));
    }

    /**
     * @template B
     * @param HK1<IOBrand, callable(A): B> $f
     * @return IO<B>
     *
     * @psalm-mutation-free
     */
    public function apply(HK1 $f): IO
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
    public static function ipure(Applicative $applicative, $a): IO
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
    public function ibind(Monad $monad, callable $f): IO
    {
        return self::fromBrand($monad->bind($this, $f));
    }

    /**
     * @template B
     * @param callable(A): HK1<IOBrand, B> $f
     * @return IO<B>
     */
    public function bind(callable $f): IO
    {
        return $this->ibind(new IOMonad(), $f);
    }
}
