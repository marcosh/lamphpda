<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

/**
 * @template A
 * @template B
 */
final class Either
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
     * @param mixed $value
     * @psalm-param A $value
     * @return self
     * @psalm-return Either<A,B>
     */
    public static function left($value): self
    {
        return new self(false, $value);
    }

    /**
     * @param mixed $value
     * @psalm-param B $value
     * @return Either
     * @psalm-return Either<A,B>
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
}
