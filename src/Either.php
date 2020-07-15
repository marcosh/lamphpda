<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\EitherBrand;
use Marcosh\LamPHPda\HK\HK;
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Apply;
use Marcosh\LamPHPda\Typeclass\Monad;

/**
 * @template A
 * @template B
 * @implements Applicative<EitherBrand, B>
 * @implements Monad<EitherBrand, B>
 * @psalm-immutable
 */
final class Either implements Applicative, Monad
{
    /**
     * @var bool
     */
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
     * @param mixed $leftValue
     * @psalm-param A|null $leftValue
     *
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
     * @psalm-param Apply<EitherBrand, callable(B): C> $f
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
            fn ($a) => self::left($a)
            ),
            (/**
             * @psalm-param callable(B): C $f
             * @psalm-return Either<A, C>
             */
            fn ($f) => $this->eval(
                (/**
                 * @psalm-param A $a
                 * @psalm-return Either<A, C>
                 */
                static fn ($a) => self::left($a)
                ),
                (/**
                 * @psalm-param B $b
                 * @psalm-return Either<A, C>
                 */
                static fn ($b) => self::right($f($b))
                )
            )
            )
        );
    }

    /**
     * @template C
     * @psalm-param callable(B): Monad<EitherBrand, C> $f
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
            static fn ($a) => self::left($a),
            /**
             * @psalm-param B $b
             * @psalm-return Either<A, C>
             */
            static fn ($b) => self::fromBrand($f($b))
        );
    }

    /**
     * @template C
     * @psalm-param callable(A): C $ifLeft
     * @psalm-param callable(B): C $ifRight
     *
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
     * @psalm-pure
     */
    public function isLeft(): bool
    {
        return $this->eval(
            /**
             * @psalm-param A $left
             * @psalm-return bool
             */
            static fn ($left) => true,
            /**
             * @psalm-param B $right
             * @psalm-return bool
             */
            static fn ($right) => false
        );
    }

    /**
     * @psalm-pure
     */
    public function isRight(): bool
    {
        return $this->eval(
            /**
             * @psalm-param A $left
             * @psalm-return bool
             */
            static fn ($left) => false,
            /**
             * @psalm-param A $right
             * @psalm-return bool
             */
            static fn ($right) => true
        );
    }

    /**
     * @template C
     * @template D
     *
     * @param mixed $value
     * @psalm-param C $value
     * @psalm-return Either<C, D>
     * @psalm-pure
     */
    public static function left($value): Either
    {
        return new self(false, $value);
    }

    /**
     * @template C
     * @psalm-param callable(B): C $f
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
            static fn ($value) => self::left($value),
            /**
             * @psalm-param B $value
             * @psalm-return Either<A, C>
             */
            static fn ($value) => self::right($f($value))
        );
    }

    /**
     * @template C
     * @template D
     *
     * @param mixed $a
     * @psalm-param D $a
     * @psalm-return Either<C, D>
     * @psalm-pure
     */
    public static function pure($a): Either
    {
        return Either::right($a);
    }

    /**
     * @template C
     * @template D
     *
     * @param mixed $value
     * @psalm-param D $value
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
     * @psalm-param HK<EitherBrand, D> $hk
     * @psalm-return Either<C, D>
     * @psalm-pure
     */
    private static function fromBrand(HK $hk): Either
    {
        /** @var Either $hk */
        return $hk;
    }
}
