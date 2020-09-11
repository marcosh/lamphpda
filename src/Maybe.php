<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\MaybeBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Apply;
use Marcosh\LamPHPda\Typeclass\Foldable;
use Marcosh\LamPHPda\Typeclass\Functor;
use Marcosh\LamPHPda\Typeclass\Monad;

/**
 * @template A
 * @implements Functor<MaybeBrand, A>
 * @implements Apply<MaybeBrand, A>
 * @implements Applicative<MaybeBrand, A>
 * @implements Monad<MaybeBrand, A>
 * @implements Foldable<MaybeBrand, A>
 * @psalm-immutable
 */
final class Maybe implements Functor, Apply, Applicative, Monad, Foldable
{
    /** @var bool */
    private $isJust;

    /**
     * @var mixed
     * @psalm-var A|null
     */
    private $value = null;

    /**
     * @param bool $isJust
     * @param mixed $value
     * @psalm-param A|null $value
     * @psalm-pure
     */
    private function __construct(bool $isJust, $value = null)
    {
        $this->isJust = $isJust;
        $this->value = $value;
    }

    /**
     * @template B
     * @param mixed $value
     * @psalm-param B $value
     * @return Maybe
     * @psalm-return Maybe<B>
     * @psalm-pure
     */
    public static function just($value): Maybe
    {
        return new self(true, $value);
    }

    /**
     * @template B
     * @return Maybe
     * @psalm-return Maybe<B>
     * @psalm-pure
     */
    public static function nothing(): Maybe
    {
        return new self(false);
    }

    /**
     * @template B
     * @param HK1 $hk
     * @psalm-param HK1<MaybeBrand, B> $hk
     * @return Maybe
     * @psalm-return Maybe<B>
     * @psalm-pure
     */
    private static function fromBrand(HK1 $hk): Maybe
    {
        /** @var Maybe $hk */
        return $hk;
    }

    /**
     * @template B
     * @param mixed $ifNothing
     * @psalm-param B $ifNothing
     * @param callable $ifJust
     * @psalm-param callable(A): B $ifJust
     * @return mixed
     * @psalm-return B
     * @psalm-pure
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
     * @param callable $f
     * @psalm-param callable(A): B $f
     * @return Maybe
     * @psalm-return Maybe<B>
     * @psalm-pure
     */
    public function map(callable $f): Maybe
    {
        return $this->eval(
            Maybe::nothing(),
            /**
             * @psalm-param A $value
             * @psalm-return Maybe<B>
             */
            fn($value) => self::just($f($value))
        );
    }

    /**
     * @template B
     * @param Apply $f
     * @psalm-param Apply<MaybeBrand, callable(A): B> $f
     * @return Maybe
     * @psalm-return Maybe<B>
     * @psalm-pure
     */
    public function apply(Apply $f): Maybe
    {
        $f = self::fromBrand($f);

        return $this->eval(
            self::nothing(),
            (/**
             * @psalm-param A $value
             * @psalm-return Maybe<B>
             */
            fn($value) => $f->eval(
                self::nothing(),
                /**
                 * @psalm-param callable(A): B $g
                 * @psalm-return Maybe<B>
                 */
                fn($g) => self::just($g($value))
            ))
        );
    }

    /**
     * @template B
     * @param mixed $a
     * @psalm-param B $a
     * @return Maybe
     * @psalm-return Maybe<B>
     * @psalm-pure
     */
    public static function pure($a): Maybe
    {
        return self::just($a);
    }

    /**
     * @template B
     * @param callable $f
     * @psalm-param callable(A): Monad<MaybeBrand, B> $f
     * @return Maybe
     * @psalm-return Maybe<B>
     * @psalm-pure
     */
    public function bind(callable $f): Maybe
    {
        return $this->eval(
            self::nothing(),
            /**
             * @psalm-param A $value
             * @psalm-return Maybe<B>
             */
            fn($value) => self::fromBrand($f($value))
        );
    }

    /**
     * @template B
     * @param callable $op
     * @psalm-param callable(A, B): B $op
     * @param mixed $unit
     * @psalm-param B $unit
     * @return mixed
     * @psalm-return B
     */
    public function foldr(callable $op, $unit)
    {
        return $this->eval(
            $unit,
            (/**
             * @psalm-param A $value
             * @psalm-return B
             */
            fn($value) => $op($value, $unit))
        );
    }

    /**
     * @return bool
     * @psalm-pure
     */
    public function isJust(): bool
    {
        return $this->eval(
            false,
            /**
             * @psalm-param A $value
             * @psalm-return bool
             */
            fn($value) => true
        );
    }

    /**
     * @return bool
     * @psalm-pure
     */
    public function isNothing(): bool
    {
        return $this->eval(
            true,
            /**
             * @psalm-param A $value
             * @psalm-return bool
             */
            fn($value) => false
        );
    }
}
