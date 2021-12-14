<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\Brand\IdentityBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Instances\Identity\IdentityApplicative;
use Marcosh\LamPHPda\Instances\Identity\IdentityApply;
use Marcosh\LamPHPda\Instances\Identity\IdentityFoldable;
use Marcosh\LamPHPda\Instances\Identity\IdentityFunctor;
use Marcosh\LamPHPda\Instances\Identity\IdentityMonad;
use Marcosh\LamPHPda\Instances\Identity\IdentityTraversable;
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
 * @implements DefaultMonad<IdentityBrand, A>
 * @implements DefaultTraversable<IdentityBrand, A>
 *
 * @psalm-immutable
 */
final class Identity implements DefaultMonad, DefaultTraversable
{
    /**
     * @var A
     */
    private $value;

    /**
     * @param A $value
     */
    private function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @template B
     * @param B $value
     * @return Identity<B>
     *
     * @psalm-pure
     */
    public static function wrap($value): self
    {
        return new self($value);
    }

    /**
     * @template B
     * @param HK1<IdentityBrand, B> $b
     * @return Identity<B>
     *
     * @psalm-pure
     */
    public static function fromBrand(HK1 $b): self
    {
        /** @var Identity $b */
        return $b;
    }

    /**
     * @return A
     */
    public function unwrap()
    {
        return $this->value;
    }

    /**
     * @template B
     * @param Functor<IdentityBrand> $functor
     * @param callable(A): B $f
     * @return Identity<B>
     *
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function imap(Functor $functor, callable $f): self
    {
        return self::fromBrand($functor->map($f, $this));
    }

    /**
     * @template B
     * @param callable(A): B $f
     * @return Identity<B>
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function map(callable $f): self
    {
        return $this->imap(new IdentityFunctor(), $f);
    }

    /**
     * @template B
     * @param Apply<IdentityBrand> $apply
     * @param HK1<IdentityBrand, callable(A): B> $f
     * @return Identity<B>
     *
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function iapply(Apply $apply, HK1 $f): self
    {
        return self::fromBrand($apply->apply($f, $this));
    }

    /**
     * @template B
     * @param HK1<IdentityBrand, callable(A): B> $f
     * @return Identity<B>
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function apply(HK1 $f): self
    {
        return $this->iapply(new IdentityApply(), $f);
    }

    /**
     * @template B
     * @param Applicative<IdentityBrand> $applicative
     * @param B $a
     * @return Identity<B>
     */
    public static function ipure(Applicative $applicative, $a): self
    {
        return self::fromBrand($applicative->pure($a));
    }

    /**
     * @template B
     * @param B $a
     * @return Identity<B>
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public static function pure($a): self
    {
        return self::ipure(new IdentityApplicative(), $a);
    }

    /**
     * @template B
     * @param Monad<IdentityBrand> $monad
     * @param callable(A): HK1<IdentityBrand, B> $f
     * @return Identity<B>
     *
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function ibind(Monad $monad, callable $f): self
    {
        return self::fromBrand($monad->bind($this, $f));
    }

    /**
     * @template B
     * @param callable(A): HK1<IdentityBrand, B> $f
     * @return Identity<B>
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function bind(callable $f): self
    {
        return $this->ibind(new IdentityMonad(), $f);
    }

    /**
     * @template B
     * @param Foldable<IdentityBrand> $foldable
     * @param pure-callable(A, B): B $f
     * @param B $b
     * @return B
     *
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function ifoldr(Foldable $foldable, callable $f, $b)
    {
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
        return $this->ifoldr(new IdentityFoldable(), $f, $b);
    }

    /**
     * @template F of Brand
     * @template B
     * @param Traversable<IdentityBrand> $traversable
     * @param Applicative<F> $applicative
     * @param callable(A): HK1<F, B> $f
     * @return HK1<F, Identity<B>>
     *
     * @psalm-suppress InvalidArgument
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function itraverse(Traversable $traversable, Applicative $applicative, callable $f): HK1
    {
        return $applicative->map([self::class, 'fromBrand'], $traversable->traverse($applicative, $f, $this));
    }

    /**
     * @template F of Brand
     * @template B
     * @param Applicative<F> $applicative
     * @param callable(A): HK1<F, B> $f
     * @return HK1<F, Identity<B>>
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function traverse(Applicative $applicative, callable $f): HK1
    {
        return $this->itraverse(new IdentityTraversable(), $applicative, $f);
    }
}
