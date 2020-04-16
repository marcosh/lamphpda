<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\EitherBrand;
use Marcosh\LamPHPda\Typeclass\Functor;

/**
 * @template A
 * @template B
 * @implements Functor<EitherBrand, B>
 * @psalm-immutable
 */
final class Either implements Functor
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
     * @return self
     * @psalm-return self<C, D>
     * @psalm-pure
     */
    public static function left($value): self
    {
        return new self(false, $value);
    }

    /**
     * @template C
     * @template D
     * @param mixed $value
     * @psalm-param D $value
     * @return self
     * @psalm-return self<C, D>
     * @psalm-pure
     */
    public static function right($value): self
    {
        return new self(true, null, $value);
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
     * @return self
     * @psalm-return self<A, C>
     * @psalm-pure
     */
    public function map(callable $f): self
    {
        return $this->eval(
            /**
             * @psalm-param A $value
             * @psalm-return self<A, C>
             */
            fn($value) => self::left($value),
            /**
             * @psalm-param B $value
             * @psalm-return self<A, C>
             */
            fn($value) => self::right($f($value))
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
