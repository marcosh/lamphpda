<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\Brand\MaybeBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Instances\Maybe\MaybeApplicative;
use Marcosh\LamPHPda\Instances\Maybe\MaybeApply;
use Marcosh\LamPHPda\Instances\Maybe\MaybeFoldable;
use Marcosh\LamPHPda\Instances\Maybe\MaybeFunctor;
use Marcosh\LamPHPda\Instances\Maybe\MaybeMonad;
use Marcosh\LamPHPda\Instances\Maybe\MaybeTraversable;
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Apply;
use Marcosh\LamPHPda\Typeclass\DefaultInstance\DefaultMonad;
use Marcosh\LamPHPda\Typeclass\DefaultInstance\DefaultTraversable;
use Marcosh\LamPHPda\Typeclass\Foldable;
use Marcosh\LamPHPda\Typeclass\Functor;
use Marcosh\LamPHPda\Typeclass\Monad;
use Marcosh\LamPHPda\Typeclass\Traversable;

/**
 * @template-covariant A
 *
 * @implements DefaultMonad<MaybeBrand, A>
 * @implements DefaultTraversable<MaybeBrand, A>
 *
 * @psalm-immutable
 */
final class Maybe implements DefaultMonad, DefaultTraversable
{
    private bool $isJust;

    /** @var null|A */
    private $value;

    /**
     * @param null|A $value
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
    public static function just($value): self
    {
        return new self(true, $value);
    }

    /**
     * @template B
     * @return Maybe<B>
     *
     * @psalm-pure
     */
    public static function nothing(): self
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
    public static function fromBrand(HK1 $b): self
    {
        /** @var Maybe $b */
        return $b;
    }

    /**
     * this might not do what you expect when B contains null
     *
     * @template B
     * @param B|null $a
     * @return Maybe<B>
     */
    public static function fromNullable($a): self
    {
        if (null === $a) {
            return self::nothing();
        }

        return self::just($a);
    }

    /**
     * @template B
     * @param B $ifNothing
     * @param callable(A): B $ifJust
     * @return B
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
     * @param A $a
     * @return A
     */
    public function withDefault($a)
    {
        return $this->eval(
            $a,
            /**
             * @param A $a
             * @return A
             */
            static fn ($a) => $a
        );
    }

    /**
     * @template B
     * @param Functor<MaybeBrand> $functor
     * @param callable(A): B $f
     * @return Maybe<B>
     */
    public function imap(Functor $functor, callable $f): self
    {
        /** @psalm-suppress ArgumentTypeCoercion */
        return self::fromBrand($functor->map($f, $this));
    }

    /**
     * @template B
     * @param callable(A): B $f
     * @return Maybe<B>
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function map(callable $f): self
    {
        return $this->imap(new MaybeFunctor(), $f);
    }

    /**
     * @template B
     * @param Apply<MaybeBrand> $apply
     * @param HK1<MaybeBrand, callable(A): B> $f
     * @return Maybe<B>
     */
    public function iapply(Apply $apply, HK1 $f): self
    {
        /** @psalm-suppress ArgumentTypeCoercion */
        return self::fromBrand($apply->apply($f, $this));
    }

    /**
     * @template B
     * @param HK1<MaybeBrand, callable(A): B> $f
     * @return Maybe<B>
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function apply(HK1 $f): self
    {
        return $this->iapply(new MaybeApply(), $f);
    }

    /**
     * @template B
     * @param Applicative<MaybeBrand> $applicative
     * @param B $a
     * @return Maybe<B>
     */
    public static function ipure(Applicative $applicative, $a): self
    {
        return self::fromBrand($applicative->pure($a));
    }

    /**
     * @template B
     * @param B $a
     * @return Maybe<B>
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public static function pure($a): self
    {
        return self::ipure(new MaybeApplicative(), $a);
    }

    /**
     * @template B
     * @param Monad<MaybeBrand> $monad
     * @param callable(A): HK1<MaybeBrand, B> $f
     * @return Maybe<B>
     */
    public function ibind(Monad $monad, callable $f): self
    {
        /** @psalm-suppress ArgumentTypeCoercion */
        return self::fromBrand($monad->bind($this, $f));
    }

    /**
     * @template B
     * @param callable(A): HK1<MaybeBrand, B> $f
     * @return Maybe<B>
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function bind(callable $f): self
    {
        return $this->ibind(new MaybeMonad(), $f);
    }

    /**
     * @template B
     * @param Foldable<MaybeBrand> $foldable
     * @param pure-callable(A, B): B $f
     * @param B $b
     * @return B
     */
    public function ifoldr(Foldable $foldable, callable $f, $b)
    {
        /** @psalm-suppress ArgumentTypeCoercion */
        return $foldable->foldr($f, $b, $this);
    }

    /**
     * @template B
     * @param pure-callable(A, B): B $f
     * @param B $b
     * @return B
     */
    public function foldr(callable $f, $b)
    {
        return $this->ifoldr(new MaybeFoldable(), $f, $b);
    }

    /**
     * @template F of Brand
     * @template B
     * @param Traversable<MaybeBrand> $traversable
     * @param Applicative<F> $applicative
     * @param callable(A): HK1<F, B> $f
     * @return HK1<F, Maybe<B>>
     */
    public function itraverse(Traversable $traversable, Applicative $applicative, callable $f): HK1
    {
        /**
         * @psalm-suppress InvalidArgument
         * @psalm-suppress ArgumentTypeCoercion
         */
        return $applicative->map([self::class, 'fromBrand'], $traversable->traverse($applicative, $f, $this));
    }

    /**
     * @template F of Brand
     * @template B
     * @param Applicative<F> $applicative
     * @param callable(A): HK1<F, B> $f
     * @return HK1<F, Maybe<B>>
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function traverse(Applicative $applicative, callable $f): HK1
    {
        return $this->itraverse(new MaybeTraversable(), $applicative, $f);
    }
}
