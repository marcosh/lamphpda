<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\ReaderBrand;
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

/**
 * @template E
 * @template-covariant A
 *
 * @implements DefaultMonad<ReaderBrand<E>, A>
 *
 * @psalm-immutable
 */
final class Reader implements DefaultMonad
{
    /** @var callable(E): A */
    private $action;

    /**
     * @param callable(E): A $f
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
    public static function reader(callable $f): self
    {
        return new self($f);
    }

    /**
     * @template B
     * @template F
     * @param HK1<ReaderBrand<F>, B> $b
     * @return Reader<F, B>
     *
     * @psalm-pure
     */
    public static function fromBrand(HK1 $b): self
    {
        /** @var Reader<F, B> */
        return $b;
    }

    /**
     * @param E $environment
     * @return A
     */
    public function runReader($environment)
    {
        /** @psalm-suppress ImpureFunctionCall */
        return ($this->action)($environment);
    }

    /**
     * @return Reader<E, E>
     */
    public function ask(): self
    {
        return self::reader(
            /**
             * @param E $e
             * @return E
             */
            static fn ($e) => $e
        );
    }

    /**
     * @template B
     * @param Functor<ReaderBrand<E>> $functor
     * @param callable(A): B $f
     * @return Reader<E, B>
     */
    public function imap(Functor $functor, callable $f): self
    {
        return self::fromBrand($functor->map($f, $this));
    }

    /**
     * @template B
     * @param callable(A): B $f
     * @return Reader<E, B>
     */
    public function map(callable $f): self
    {
        return $this->imap(new ReaderFunctor(), $f);
    }

    /**
     * @template B
     * @param Apply<ReaderBrand<E>> $apply
     * @param HK1<ReaderBrand<E>, callable(A): B> $f
     * @return Reader<E, B>
     */
    public function iapply(Apply $apply, HK1 $f): self
    {
        return self::fromBrand($apply->apply($f, $this));
    }

    /**
     * @template B
     * @param HK1<ReaderBrand<E>, callable(A): B> $f
     * @return Reader<E, B>
     */
    public function apply(HK1 $f): self
    {
        return $this->iapply(new ReaderApply(), $f);
    }

    /**
     * @template B
     * @template F
     * @param Applicative<ReaderBrand<F>> $applicative
     * @param B $a
     * @return Reader<F, B>
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
     */
    public function ibind(Monad $monad, callable $f): self
    {
        return self::fromBrand($monad->bind($this, $f));
    }

    /**
     * @template B
     * @param callable(A): HK1<ReaderBrand<E>, B> $f
     * @return Reader<E, B>
     */
    public function bind(callable $f): self
    {
        return $this->ibind(new ReaderMonad(), $f);
    }
}
