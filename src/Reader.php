<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Instances\Reader\ReaderApplicative;
use Marcosh\LamPHPda\Instances\Reader\ReaderApply;
use Marcosh\LamPHPda\Instances\Reader\ReaderFunctor;
use Marcosh\LamPHPda\Instances\Reader\ReaderMonad;
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Apply;
use Marcosh\LamPHPda\Typeclass\DefaultInstance\DefaultMonad;
use Marcosh\LamPHPda\Typeclass\Functor;
use Marcosh\LamPHPda\Typeclass\Monad;
use Marcosh\LamPHPda\Brand\ReaderBrand;

/**
 * @template E
 * @template A
 *
 * @implements DefaultMonad<ReaderBrand<E>, A>
 *
 * @psalm-immutable
 */
final class Reader implements DefaultMonad
{
    /**
     * @var callable(E): A
     */
    private $action;

    /**
     * @param callable(E): A $f
     *
     * @psalm-mutation-free
     */
    private function __construct(callable $f)
    {
        $this->action = $f;
    }

    /**
     * @template B
     * @param callable(E): B $f
     * @return Reader<E, B>
     *
     * @psalm-pure
     */
    public static function reader(callable $f): Reader
    {
        return new self($f);
    }

    /**
     * @template B
     * @param HK1<ReaderBrand<E>, B> $b
     * @return Reader<E, B>
     *
     * @psalm-pure
     */
    public static function fromBrand(HK1 $b): Reader
    {
        /** @var Reader<E, B> $b */
        return $b;
    }

    /**
     * @param E $environment
     * @return A
     *
     * @psalm-mutation-free
     */
    public function runReader($environment)
    {
        return ($this->action)($environment);
    }

    /**
     * @template B
     * @param Functor<ReaderBrand<E>> $functor
     * @param callable(A): B $f
     * @return Reader<E, B>
     *
     * @psalm-mutation-free
     */
    public function imap(Functor $functor, callable $f): Reader
    {
        return self::fromBrand($functor->map($f, $this));
    }

    /**
     * @template B
     * @param callable(A): B $f
     * @return Reader<E, B>
     *
     * @psalm-mutation-free
     */
    public function map(callable $f): Reader
    {
        return $this->imap(new ReaderFunctor(), $f);
    }

    /**
     * @template B
     * @param Apply<ReaderBrand<E>> $apply
     * @param HK1<ReaderBrand<E>, callable(A): B> $f
     * @return Reader<E, B>
     *
     * @psalm-mutation-free
     */
    public function iapply(Apply $apply, HK1 $f): Reader
    {
        return self::fromBrand($apply->apply($f, $this));
    }

    /**
     * @template B
     * @param HK1<ReaderBrand<E>, callable(A): B> $f
     * @return Reader<E, B>
     *
     * @psalm-pure
     */
    public function apply(HK1 $f): Reader
    {
        return $this->iapply(new ReaderApply(), $f);
    }

    /**
     * @template B
     * @param Applicative<ReaderBrand<E>> $applicative
     * @param B $a
     * @return Reader<E, B>
     *
     * @psalm-pure
     */
    public static function ipure(Applicative $applicative, $a): Reader
    {
        return self::fromBrand($applicative->pure($a));
    }

    /**
     * @template B
     * @param B $a
     * @return Reader<E, B>
     *
     * @psalm-pure
     */
    public static function pure($a): self
    {
        return self::ipure(new ReaderApplicative(), $a);
    }

    /**
     * @template B
     * @param Monad<ReaderBrand<E>> $monad
     * @param callable(A): HK1<ReaderBrand<E>, B> $f
     * @return Reader<E, B>
     *
     * @psalm-pure
     */
    public function ibind(Monad $monad, callable $f): Reader
    {
        return self::fromBrand($monad->bind($this, $f));
    }

    /**
     * @template B
     * @param callable(A): Monad<ReaderBrand<E>> $f
     * @return Reader<E, B>
     *
     * @psalm-pure
     */
    public function bind(callable $f): Reader
    {
        return $this->ibind(new ReaderMonad(), $f);
    }
}
