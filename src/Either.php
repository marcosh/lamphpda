<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\Brand\EitherBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Instances\Either\EitherApplicative;
use Marcosh\LamPHPda\Instances\Either\EitherApply;
use Marcosh\LamPHPda\Instances\Either\EitherFoldable;
use Marcosh\LamPHPda\Instances\Either\EitherFunctor;
use Marcosh\LamPHPda\Instances\Either\EitherMonad;
use Marcosh\LamPHPda\Instances\Either\EitherTraversable;
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Apply;
use Marcosh\LamPHPda\Typeclass\DefaultInstance\DefaultApply;
use Marcosh\LamPHPda\Typeclass\Foldable;
use Marcosh\LamPHPda\Typeclass\Functor;
use Marcosh\LamPHPda\Typeclass\Monad;
use Marcosh\LamPHPda\Typeclass\Traversable;

/**
 * @template A
 * @template B
 * @implements DefaultApply<EitherBrand<A>, B>
 */
final class Either implements DefaultApply
{
    /** @var bool */
    private $isRight;

    /**
     * @var A|null
     */
    private $leftValue;

    /**
     * @var B|null
     */
    private $rightValue;

    /**
     * @param bool $isRight
     * @param A|null $leftValue
     * @param B|null $rightValue
     *
     * @psalm-mutation-free
     */
    private function __construct(bool $isRight, $leftValue = null, $rightValue = null)
    {
        $this->isRight = $isRight;
        $this->leftValue = $leftValue;
        $this->rightValue = $rightValue;
    }

    /**
     * @template C
     * @template D
     * @param C $value
     * @return Either<C, D>
     *
     * @psalm-pure
     */
    public static function left($value): Either
    {
        return new self(false, $value);
    }

    /**
     * @template C
     * @template D
     * @param D $value
     * @return Either<C, D>
     *
     * @psalm-pure
     */
    public static function right($value): Either
    {
        return new self(true, null, $value);
    }

    /**
     * @template C
     * @template D
     * @param HK1<EitherBrand<C>, D> $hk
     * @return Either<C, D>
     *
     * @psalm-pure
     */
    public static function fromBrand(HK1 $hk): Either
    {
        /** @var Either $hk */
        return $hk;
    }

    /**
     * @template C
     * @param callable(A): C $ifLeft
     * @param callable(B): C $ifRight
     * @return C
     *
     * @psalm-mutation-free
     */
    public function eval(
        callable $ifLeft,
        callable $ifRight
    ) {
        if ($this->isRight) {
            /** @psalm-suppress PossiblyNullArgument */
            return $ifRight($this->rightValue);
        }

        /** @psalm-suppress PossiblyNullArgument */
        return $ifLeft($this->leftValue);
    }

    /**
     * @template C
     * @param Functor<EitherBrand<A>> $functor
     * @param callable(B): C $f
     * @return Either<A, C>
     *
     * @psalm-mutation-free
     */
    public function imap(Functor $functor, callable $f): Either
    {
        /** @psalm-suppress ArgumentTypeCoercion */
        return self::fromBrand($functor->map($f, $this));
    }

    /**
     * @template C
     * @param callable(B): C $f
     * @return Either<A, C>
     *
     * @psalm-mutation-free
     */
    public function map(callable $f): Either
    {
        return $this->imap(new EitherFunctor(), $f);
    }

    /**
     * @template C
     * @param Apply<EitherBrand<A>> $apply
     * @param HK1<EitherBrand<A>, callable(B): C> $f
     * @return Either<A, C>
     *
     * @psalm-mutation-free
     */
    public function iapply(Apply $apply, HK1 $f): Either
    {
        return self::fromBrand($apply->apply($f, $this));
    }

    /**
     * @template C
     * @param HK1<EitherBrand<A>, callable(B): C> $f
     * @return Either<A, C>
     *
     * @psalm-mutation-free
     */
    public function apply(HK1 $f): Either
    {
        return $this->iapply(new EitherApply(), $f);
    }

    /**
     * @template C
     * @template D
     * @param Applicative<EitherBrand<A>> $applicative
     * @param D $a
     * @return Either<C, D>
     */
    public static function ipure(Applicative $applicative, $a): Either
    {
        return self::fromBrand($applicative->pure($a));
    }

    /**
     * @template C
     * @template D
     * @param D $a
     * @return Either<C, D>
     */
    public static function pure($a): Either
    {
        return self::ipure(new EitherApplicative(), $a);
    }

    /**
     * @template C
     * @param Monad<EitherBrand<A>> $monad
     * @param callable(B): HK1<EitherBrand<A>, C> $f
     * @return Either<A, C>
     */
    public function ibind(Monad $monad, callable $f): Either
    {
        return self::fromBrand($monad->bind($this, $f));
    }

    /**
     * @template C
     * @param callable(B): HK1<EitherBrand<A>, C> $f
     * @return Either<A, C>
     */
    public function bind(callable $f): Either
    {
        return $this->ibind(new EitherMonad(), $f);
    }

    /**
     * @template C
     * @param Foldable<EitherBrand<A>> $foldable
     * @param callable(B, C): C $f
     * @param C $b
     * @return C
     */
    public function ifoldr(Foldable $foldable, callable $f, $b)
    {
        return $foldable->foldr($f, $b, $this);
    }

    /**
     * @template C
     * @param callable(B, C): C $f
     * @param C $b
     * @return C
     */
    public function foldr(callable $f, $b)
    {
        return $this->ifoldr(new EitherFoldable(), $f, $b);
    }

    /**
     * @template F of Brand
     * @template C
     * @param Traversable<EitherBrand<A>> $traversable
     * @param Applicative<F> $applicative
     * @param callable(B): HK1<F, C> $f
     * @return HK1<F, Either<A, C>>
     *
     * @psalm-suppress InvalidReturnType
     */
    public function itraverse(Traversable $traversable, Applicative $applicative, callable $f): HK1
    {
        /**
         * @psalm-suppress InvalidReturnStatement
         * @psalm-suppress InvalidArgument
         */
        return $applicative->map([Either::class, 'fromBrand'], $traversable->traverse($applicative, $f, $this));
    }

    /**
     * @template F of Brand
     * @template C
     * @param Applicative<F> $applicative
     * @param callable(B): HK1<F, C> $f
     * @return HK1<F, Either<A, C>>
     */
    public function traverse(Applicative $applicative, callable $f): HK1
    {
        return $this->itraverse(new EitherTraversable(), $applicative, $f);
    }
}
