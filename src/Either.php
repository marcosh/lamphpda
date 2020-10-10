<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\EitherBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Apply;
use Marcosh\LamPHPda\Typeclass\Foldable;
use Marcosh\LamPHPda\Typeclass\Functor;
use Marcosh\LamPHPda\Typeclass\Monad;

/**
 * @template A
 * @template B
 * @implements Functor<EitherBrand, B>
 * @implements Apply<EitherBrand, B>
 * @implements Applicative<EitherBrand, B>
 * @implements Monad<EitherBrand, B>
 * @implements Foldable<EitherBrand, B>
 * @psalm-immutable
 */
final class Either implements Functor, Apply, Applicative, Monad, Foldable
{
    /** @var bool */
    private $isRight;

    /**
     * @var mixed
     * @psalm-var A|null
     */
    private $leftValue;

    /**
     * @var mixed
     * @psalm-var B|null
     */
    private $rightValue;

    /**
     * @param bool $isRight
     * @param mixed $leftValue
     * @psalm-param A|null $leftValue
     * @param mixed $rightValue
     * @psalm-param B|null $rightValue
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
     * @param mixed $value
     * @psalm-param C $value
     * @return Either
     * @psalm-return Either<C, D>
     * @psalm-pure
     */
    public static function left($value): Either
    {
        return new self(false, $value);
    }

    /**
     * @template C
     * @template D
     * @param mixed $value
     * @psalm-param D $value
     * @return Either
     * @psalm-return Either<C, D>
     * @psalm-pure
     */
    public static function right($value): Either
    {
        return new self(true, null, $value);
    }

    /**
     * @template C
     * @template D
     * @param HK1 $hk
     * @psalm-param HK1<EitherBrand, D> $hk
     * @return Either
     * @psalm-return Either<C, D>
     * @psalm-pure
     */
    private static function fromBrand(HK1 $hk): Either
    {
        /** @var Either $hk */
        return $hk;
    }

    /**
     * @template C
     * @param callable $ifLeft
     * @psalm-param callable(A): C $ifLeft
     * @param callable $ifRight
     * @psalm-param callable(B): C $ifRight
     * @return mixed
     * @psalm-return C
     * @psalm-pure
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
     * @param callable $f
     * @psalm-param callable(B): C $f
     * @return Either
     * @psalm-return Either<A, C>
     * @psalm-pure
     */
    public function map(callable $f): Either
    {
        return $this->eval(
            /**
             * @psalm-param A $value
             * @psalm-return Either<A, C>
             */
            fn($value) => self::left($value),
            /**
             * @psalm-param B $value
             * @psalm-return Either<A, C>
             */
            fn($value) => self::right($f($value))
        );
    }

    /**
     * @template C
     * @param callable $f
     * @psalm-param callable(A): C $f
     * @return Either
     * @psalm-return Either<C, B>
     * @psalm-pure
     */
    public function mapLeft(callable $f): Either
    {
        return $this->eval(
            /**
             * @psalm-param A $value
             * @psalm-return Either<C, B>
             */
            fn($value) => self::left($f($value)),
            /**
             * @psalm-param B $value
             * @psalm-return Either<C, B>
             */
            fn($value) => self::right($value)
        );
    }

    /**
     * @template C
     * @param Apply $f
     * @psalm-param Apply<EitherBrand, callable(B): C> $f
     * @return Either
     * @psalm-return Either<A, C>
     * @psalm-pure
     */
    public function apply(Apply $f): Either
    {
        $f = self::fromBrand($f);

        return $f->eval(
            (/**
             * @psalm-param A $a
             * @psalm-return Either<A, C>
             */
            fn($a) => self::left($a)),
            (/**
             * @psalm-param callable(B): C $f
             * @psalm-return Either<A, C>
             */
            fn($f) => $this->eval(
                (/**
                 * @psalm-param A $a
                 * @psalm-return Either<A, C>
                 */
                fn($a) => self::left($a)),
                (/**
                 * @psalm-param B $b
                 * @psalm-return Either<A, C>
                 */
                fn($b) => self::right($f($b)))
            ))
        );
    }

    /**
     * @template C
     * @template D
     * @param mixed $a
     * @psalm-param D $a
     * @return Either
     * @psalm-return Either<C, D>
     * @psalm-pure
     */
    public static function pure($a): Either
    {
        return Either::right($a);
    }

    /**
     * @template C
     * @param callable $f
     * @psalm-param callable(B): Monad<EitherBrand, C> $f
     * @return Either
     * @psalm-return Either<A, C>
     * @psalm-pure
     */
    public function bind(callable $f): Either
    {
        return $this->eval(
            /**
             * @psalm-param A $a
             * @psalm-return Either<A, C>
             */
            fn($a) => self::left($a),
            /**
             * @psalm-param B $b
             * @psalm-return Either<A, C>
             */
            fn($b) => self::fromBrand($f($b))
        );
    }

    /**
     * @template C
     * @param callable $op
     * @psalm-param callable(B, C): C $op
     * @param mixed $unit
     * @psalm-param C $unit
     * @return mixed
     * @psalm-return C
     */
    public function foldr(callable $op, $unit)
    {
        return $this->eval(
            (/**
             * @psalm-param A $left
             * @psalm-return C
             */
            fn($left) => $unit),
            (/**
             * @psalm-param B $right
             * @psalm-return C
             */
            fn($right) => $op($right, $unit))
        );
    }

    /**
     * @return bool
     * @psalm-pure
     */
    public function isLeft(): bool
    {
        return $this->eval(
            /**
             * @psalm-param A $left
             * @psalm-return bool
             */
            fn($left) => true,
            /**
             * @psalm-param B $right
             * @psalm-return bool
             */
            fn($right) => false
        );
    }

    /**
     * @return bool
     * @psalm-pure
     */
    public function isRight(): bool
    {
        return $this->eval(
            /**
             * @psalm-param A $left
             * @psalm-return bool
             */
            fn($left) => false,
            /**
             * @psalm-param A $right
             * @psalm-return bool
             */
            fn($right) => true
        );
    }
}
