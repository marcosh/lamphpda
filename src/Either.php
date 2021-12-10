<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\Brand\EitherBrand;
use Marcosh\LamPHPda\Brand\EitherBrand2;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\HK\HK2Covariant;
use Marcosh\LamPHPda\Instances\Either\EitherApplicative;
use Marcosh\LamPHPda\Instances\Either\EitherApply;
use Marcosh\LamPHPda\Instances\Either\EitherBifunctor;
use Marcosh\LamPHPda\Instances\Either\EitherFoldable;
use Marcosh\LamPHPda\Instances\Either\EitherFunctor;
use Marcosh\LamPHPda\Instances\Either\EitherMonad;
use Marcosh\LamPHPda\Instances\Either\EitherTraversable;
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Apply;
use Marcosh\LamPHPda\Typeclass\Bifunctor;
use Marcosh\LamPHPda\Typeclass\DefaultInstance\DefaultMonad;
use Marcosh\LamPHPda\Typeclass\DefaultInstance\DefaultTraversable;
use Marcosh\LamPHPda\Typeclass\Foldable;
use Marcosh\LamPHPda\Typeclass\Functor;
use Marcosh\LamPHPda\Typeclass\Monad;
use Marcosh\LamPHPda\Typeclass\Traversable;

/**
 * @template-covariant A
 * @template-covariant B
 *
 * @implements DefaultMonad<EitherBrand<A>, B>
 * @implements DefaultTraversable<EitherBrand<A>, B>
 * @implements HK2Covariant<EitherBrand2, A, B>
 *
 * @psalm-immutable
 */
final class Either implements DefaultMonad, DefaultTraversable, HK2Covariant
{
    /**
     * @var bool
     */
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
     *
     * @param HK1<EitherBrand<A>, callable(B): C> $f
     *
     * @return Either<A, C>
     *
     * @psalm-mutation-free
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function apply(HK1 $f): Either
    {
        return $this->iapply(new EitherApply(), $f);
    }

    /**
     * @template C
     * @template D
     *
     * @param callable(A): C $f
     * @param callable(B): D $g
     *
     * @return Either<C, D>
     *
     * @psalm-mutation-free
     */
    public function biMap(callable $f, callable $g): Either
    {
        return $this->ibiMap(new EitherBifunctor(), $f, $g);
    }

    /**
     * @template C
     *
     * @param callable(B): HK1<EitherBrand<A>, C> $f
     *
     * @return Either<A, C>
     *
     * @psalm-mutation-free
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function bind(callable $f): Either
    {
        return $this->ibind(new EitherMonad(), $f);
    }

    /**
     * @template C
     *
     * @param callable(A): C $ifLeft
     * @param callable(B): C $ifRight
     *
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
     *
     * @param pure-callable(B, C): C $f
     * @param C $b
     *
     * @return C
     *
     * @psalm-mutation-free
     */
    public function foldr(callable $f, $b)
    {
        return $this->ifoldr(new EitherFoldable(), $f, $b);
    }

    /**
     * @template C
     * @template D
     *
     * @param HK1<EitherBrand<C>, D> $hk
     *
     * @return Either<C, D>
     *
     * @psalm-pure
     */
    public static function fromBrand(HK1 $hk): Either
    {
        /** @var Either<C, D> */
        return $hk;
    }

    /**
     * @template C
     * @template D
     *
     * @param HK2Covariant<EitherBrand2, C, D> $hk
     *
     * @return Either<C, D>
     *
     * @psalm-pure
     */
    public static function fromBrand2(HK2Covariant $hk): Either
    {
        /** @var Either<C, D> */
        return $hk;
    }

    /**
     * @template C
     *
     * @param Apply<EitherBrand<A>> $apply
     * @param HK1<EitherBrand<A>, callable(B): C> $f
     *
     * @return Either<A, C>
     *
     * @psalm-mutation-free
     */
    public function iapply(Apply $apply, HK1 $f): Either
    {
        /** @psalm-suppress ArgumentTypeCoercion */
        return self::fromBrand($apply->apply($f, $this));
    }

    /**
     * @template C
     * @template D
     *
     * @param Bifunctor<EitherBrand2> $bifunctor
     * @param callable(A): C $f
     * @param callable(B): D $g
     *
     * @return Either<C, D>
     *
     * @psalm-mutation-free
     */
    public function ibiMap(Bifunctor $bifunctor, callable $f, callable $g): Either
    {
        return self::fromBrand2($bifunctor->biMap($f, $g, $this));
    }

    /**
     * @template C
     *
     * @param Monad<EitherBrand<A>> $monad
     * @param callable(B): HK1<EitherBrand<A>, C> $f
     *
     * @return Either<A, C>
     *
     * @psalm-mutation-free
     */
    public function ibind(Monad $monad, callable $f): Either
    {
        /** @psalm-suppress ArgumentTypeCoercion */
        return self::fromBrand($monad->bind($this, $f));
    }

    /**
     * @template C
     *
     * @param Foldable<EitherBrand<A>> $foldable
     * @param pure-callable(B, C): C $f
     * @param C $b
     *
     * @return C
     *
     * @psalm-mutation-free
     */
    public function ifoldr(Foldable $foldable, callable $f, $b)
    {
        /** @psalm-suppress ArgumentTypeCoercion */
        return $foldable->foldr($f, $b, $this);
    }

    /**
     * @template C
     *
     * @param Functor<EitherBrand<A>> $functor
     * @param pure-callable(B): C $f
     *
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
     * @template D
     *
     * @param Applicative<EitherBrand<A>> $applicative
     * @param D $a
     *
     * @return Either<C, D>
     *
     * @psalm-mutation-free
     */
    public static function ipure(Applicative $applicative, $a): Either
    {
        return self::fromBrand($applicative->pure($a));
    }

    /**
     * @template F of Brand
     * @template C
     *
     * @param Traversable<EitherBrand<A>> $traversable
     * @param Applicative<F> $applicative
     * @param callable(B): HK1<F, C> $f
     *
     * @return HK1<F, Either<A, C>>
     *
     * @psalm-mutation-free
     *
     * @psalm-suppress InvalidReturnType
     */
    public function itraverse(Traversable $traversable, Applicative $applicative, callable $f): HK1
    {
        /**
         * @psalm-suppress InvalidReturnStatement
         * @psalm-suppress InvalidArgument
         * @psalm-suppress ArgumentTypeCoercion
         */
        return $applicative->map([Either::class, 'fromBrand'], $traversable->traverse($applicative, $f, $this));
    }

    /**
     * @template C
     * @template D
     *
     * @param C $value
     *
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
     *
     * @param pure-callable(B): C $f
     *
     * @return Either<A, C>
     *
     * @psalm-mutation-free
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function map(callable $f): Either
    {
        return $this->imap(new EitherFunctor(), $f);
    }

    /**
     * @template C
     *
     * @param callable(A): C $f
     *
     * @return Either<C, B>
     *
     * @psalm-mutation-free
     */
    public function mapLeft(callable $f): Either
    {
        return $this->biMap(
            $f,
            /**
             * @param B $b
             *
             * @return B
             */
            fn ($b) => $b
        );
    }

    /**
     * @template C
     * @template D
     *
     * @param D $a
     *
     * @return Either<C, D>
     *
     * @psalm-mutation-free
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public static function pure($a): Either
    {
        return self::ipure(new EitherApplicative(), $a);
    }

    /**
     * @template C
     * @template D
     *
     * @param D $value
     *
     * @return Either<C, D>
     *
     * @psalm-pure
     */
    public static function right($value): Either
    {
        return new self(true, null, $value);
    }

    /**
     * @template F of Brand
     * @template C
     *
     * @param Applicative<F> $applicative
     * @param callable(B): HK1<F, C> $f
     *
     * @return HK1<F, Either<A, C>>
     *
     * @psalm-mutation-free
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function traverse(Applicative $applicative, callable $f): HK1
    {
        return $this->itraverse(new EitherTraversable(), $applicative, $f);
    }
}
