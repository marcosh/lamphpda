<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\MaybeBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Instances\Maybe\MaybeMonad;
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Apply;
use Marcosh\LamPHPda\Typeclass\DefaultInstance\DefaultMonad;
use Marcosh\LamPHPda\Typeclass\Functor;
use Marcosh\LamPHPda\Typeclass\Monad;

/**
 * @template A
 * @implements DefaultMonad<MaybeBrand, A>
 */
final class Maybe implements DefaultMonad
{
    /**
     * @var bool
     *
     * @psalm-readonly
     */
    private $isJust;

    /**
     * @var A|null
     *
     * @psalm-readonly
     */
    private $value = null;

    /**
     * @param bool $isJust
     * @param A|null $value
     *
     * @psalm-mutation-free
     */
    private function __construct(bool $isJust, $value = null)
    {
        $this->isJust = $isJust;
        $this->value = $value;
    }

    /**
     * @template B
     * @param B $value
     * @return Maybe<B>
     *
     * @psalm-pure
     */
    public static function just($value): Maybe
    {
        return new self(true, $value);
    }

    /**
     * @template B
     * @return Maybe<B>
     *
     * @psalm-pure
     */
    public static function nothing(): Maybe
    {
        return new self(false);
    }

    /**
     * @template B
     * @param HK1<MaybeBrand, B> $b
     * @return Maybe<B>
     *
     * @psalm-pure
     */
    public static function fromBrand(HK1 $b): Maybe
    {
        /** @var Maybe $b */
        return $b;
    }

    /**
     * @template B
     * @param B $ifNothing
     * @param callable(A): B $ifJust
     * @return B
     *
     * @psalm-mutation-free
     */
    public function eval(
        $ifNothing,
        callable $ifJust
    ) {
        if ($this->isJust) {
            /** @psalm-suppress PossiblyNullArgument */
            return $ifJust($this->value);
        }

        return $ifNothing;
    }

    /**
     * @template B
     * @param Functor<MaybeBrand> $functor
     * @param callable(A): B $f
     * @return Maybe<B>
     *
     * @psalm-mutation-free
     */
    public function imap(Functor $functor, callable $f): Maybe
    {
        return self::fromBrand($functor->map($f, $this));
    }

    /**
     * @template B
     * @param callable(A): B $f
     * @return Maybe<B>
     *
     * @psalm-mutation-free
     */
    public function map(callable $f): Maybe
    {
        return $this->imap(new MaybeMonad(), $f);
    }

    /**
     * @template B
     * @param Apply<MaybeBrand> $apply
     * @param HK1<MaybeBrand, callable(A): B> $f
     * @return Maybe<B>
     *
     * @psalm-mutation-free
     */
    public function iapply(Apply $apply, HK1 $f): Maybe
    {
        return self::fromBrand($apply->apply($f, $this));
    }

    /**
     * @template B
     * @param HK1<MaybeBrand, callable(A): B> $f
     * @return Maybe<B>
     *
     * @psalm-mutation-free
     */
    public function apply(HK1 $f): Maybe
    {
        return $this->iapply(new MaybeMonad(), $f);
    }

    /**
     * @template B
     * @param Applicative<MaybeBrand> $applicative
     * @param B $a
     * @return Maybe<B>
     */
    public static function ipure(Applicative $applicative, $a): Maybe
    {
        return self::fromBrand($applicative->pure($a));
    }

    /**
     * @template B
     * @param B $a
     * @return Maybe<B>
     */
    public static function pure($a): Maybe
    {
        return self::ipure(new MaybeMonad(), $a);
    }

    /**
     * @template B
     * @param Monad<MaybeBrand> $monad
     * @param callable(A): HK1<MaybeBrand, B> $f
     * @return Maybe<B>
     */
    public function ibind(Monad $monad, callable $f): Maybe
    {
        return self::fromBrand($monad->bind($this, $f));
    }

    /**
     * @template B
     * @param callable(A): HK1<MaybeBrand, B> $f
     * @return Maybe<B>
     */
    public function bind(callable $f): Maybe
    {
        return $this->ibind(new MaybeMonad(), $f);
    }
}
