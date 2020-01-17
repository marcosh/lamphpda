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
     * @template C
     * @template D
     * @param mixed $value
     * @psalm-param C $value
     * @return self
     * @psalm-return Either<C,D>
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
     * @return Either
     * @psalm-return Either<C,D>
     */
    public static function right($value): self
    {
        return new self(true, null, $value);
    }

    /**
     * @template C
     * @template D
     * @template E
     * @param callable $ifLeft
     * @psalm-param callable(C): E $ifLeft
     * @param callable $ifRight
     * @psalm-param callable(D): E $ifRight
     * @return mixed
     * @psalm-return E
     */
    public function eval(
        callable $ifLeft,
        callable $ifRight
    ) {
        if ($this->isRight) {
            /** @psalm-suppress PossiblyInvalidArgument */
            return $ifRight($this->rightValue);
        }

        /** @psalm-suppress PossiblyInvalidArgument */
        return $ifLeft($this->leftValue);
    }
}
