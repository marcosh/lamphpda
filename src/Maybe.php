<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

/**
 * @template A
 * @implements Functor<A>
 */
final class Maybe implements Functor
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
     * @return self
     * @psalm-return self<B>
     */
    public static function just($value): self
    {
        return new self(true, $value);
    }

    /**
     * @template B
     * @return self
     * @psalm-return self<B>
     */
    public static function nothing(): self
    {
        return new self(false);
    }

    /**
     * @template B
     * @param mixed $ifNothing
     * @psalm-param B $ifNothing
     * @param callable $ifJust
     * @psalm-param callable(A): B $ifJust
     * @return mixed
     * @psalm-return B
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
     * @return self
     * @psalm-return self<B>
     */
    public function map(callable $f): self
    {
        return $this->eval(
            Maybe::nothing(),
            /**
             * @psalm-param A $value
             * @psalm-return self<B>
             */
            fn($value) => self::just($f($value))
        );
    }
}
